<?php

use App\Actions\Billing\ChangePlan;
use App\Actions\Billing\MarkInvoicePaid;
use App\Actions\Billing\RunBillingCycle;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Jobs\ChangeHostingPackage;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HostingUsage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    Queue::fake();
    Notification::fake();

    $this->user = User::factory()->create();
    $this->team = $this->user->currentTeam;
    $this->starter = Plan::factory()->create(['name' => 'Starter', 'slug' => 'starter', 'price_ngwee' => 19900, 'storage_gb' => 5, 'websites' => 1, 'databases' => 1, 'sort_order' => 1]);
    $this->business = Plan::factory()->create(['name' => 'Business', 'slug' => 'business', 'price_ngwee' => 49900, 'storage_gb' => 20, 'websites' => 5, 'databases' => 10, 'sort_order' => 2]);

    $this->travelTo(now()->startOfDay());
    $this->subscription = Subscription::factory()->active()->create([
        'team_id' => $this->team->id,
        'plan_id' => $this->business->id,
        'current_period_ends_at' => now()->addMonthNoOverflow(),
    ]);
});

function planRoute(Subscription $subscription): string
{
    return route('subscriptions.plan.update', ['current_team' => $subscription->team->slug, 'subscription' => $subscription]);
}

function fakeUsage(?array $usage): void
{
    $mock = Mockery::mock(HostingUsage::class);
    $mock->shouldReceive('for')->andReturn($usage);
    app()->instance(HostingUsage::class, $mock);
}

function usage(int $diskMb, int $websites, int $databases): array
{
    return [
        'disk' => ['used' => $diskMb, 'limit' => 20480],
        'bandwidth' => ['used' => 0, 'limit' => 76800],
        'websites' => ['used' => $websites, 'limit' => 5],
        'databases' => ['used' => $databases, 'limit' => 10],
    ];
}

test('the plan page offers every plan with the prorated upgrade cost', function () {
    $this->subscription->update(['plan_id' => $this->starter->id]);

    $this->actingAs($this->user)
        ->get(route('subscriptions.plan.edit', ['current_team' => $this->team->slug, 'subscription' => $this->subscription]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('billing/ChangePlan')
            ->where('subscription.plan', 'starter')
            ->has('plans', 2)
            ->where('plans.1.isUpgrade', true)
            ->where('plans.1.upgradeCost', '300.00'));
});

test('upgrade cost covers only the rest of the period', function () {
    $this->subscription->update(['plan_id' => $this->starter->id]);
    $changePlan = app(ChangePlan::class);

    expect($changePlan->upgradeCost($this->subscription->fresh('plan'), $this->business))->toBe(30000);

    $this->travelTo($this->subscription->current_period_ends_at->subDays(1));

    expect($changePlan->upgradeCost($this->subscription->fresh('plan'), $this->business))->toBeLessThan(1500)->toBeGreaterThanOrEqual(100);
});

test('upgrading issues an invoice and only switches once it is paid', function () {
    $this->subscription->update(['plan_id' => $this->starter->id]);

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'business'])->assertRedirect();

    $invoice = Invoice::where('type', InvoiceType::PlanChange)->sole();
    expect($invoice)->plan_id->toBe($this->business->id)->amount_ngwee->toBe(30000)
        ->and($this->subscription->fresh()->plan_id)->toBe($this->starter->id);

    app(MarkInvoicePaid::class)->handle($invoice);

    expect($this->subscription->fresh())
        ->plan_id->toBe($this->business->id)
        ->current_period_ends_at->toDateString()->toBe(now()->addMonthNoOverflow()->toDateString());

    Queue::assertPushed(ChangeHostingPackage::class);
});

test('a paid upgrade reprices an already issued renewal', function () {
    $this->subscription->update(['plan_id' => $this->starter->id]);
    $renewal = Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'plan_id' => $this->starter->id, 'amount_ngwee' => 19900]);
    $upgrade = app(ChangePlan::class)->handle($this->subscription->fresh('plan'), $this->business);

    app(MarkInvoicePaid::class)->handle($upgrade);

    expect($renewal->fresh())->amount_ngwee->toBe(49900)->plan_id->toBe($this->business->id);
});

test('downgrades are scheduled for the next renewal', function () {
    fakeUsage(usage(1000, 1, 1));

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'starter'])
        ->assertRedirect(route('dashboard', ['current_team' => $this->team->slug]));

    expect($this->subscription->fresh())
        ->plan_id->toBe($this->business->id)
        ->scheduled_plan_id->toBe($this->starter->id);

    $this->travelTo($this->subscription->current_period_ends_at->subDays(3));
    app(RunBillingCycle::class)->issueRenewalInvoices();

    $renewal = $this->subscription->invoices()->sole();
    expect($renewal)->amount_ngwee->toBe(19900)->plan_id->toBe($this->starter->id);

    app(MarkInvoicePaid::class)->handle($renewal);

    expect($this->subscription->fresh())->plan_id->toBe($this->starter->id)->scheduled_plan_id->toBeNull();
    Queue::assertPushed(ChangeHostingPackage::class);
});

test('downgrades are refused when current usage would not fit', function (array $usage, string $message) {
    fakeUsage($usage);

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'starter'])
        ->assertSessionHasErrors(['plan' => "You can't move to Starter yet: {$message}."]);

    expect($this->subscription->fresh()->scheduled_plan_id)->toBeNull();
})->with([
    'disk' => [usage(6000, 1, 1), "you're using more than 5GB of storage"],
    'websites' => [usage(100, 3, 1), 'you have more than 1 websites'],
    'databases' => [usage(100, 1, 4), 'you have more than 1 databases'],
]);

test('downgrades are refused when usage cannot be read', function () {
    fakeUsage(null);

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'starter'])
        ->assertSessionHasErrors('plan');
});

test('choosing the current plan withdraws a scheduled downgrade', function () {
    $this->subscription->update(['scheduled_plan_id' => $this->starter->id]);
    $renewal = Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'plan_id' => $this->starter->id, 'amount_ngwee' => 19900]);

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'business']);

    expect($this->subscription->fresh()->scheduled_plan_id)->toBeNull()
        ->and($renewal->fresh()->amount_ngwee)->toBe(49900);
});

test('plan changes need active, paid-up hosting that is not being cancelled', function (array $attributes) {
    $this->subscription->update($attributes);

    $this->actingAs($this->user)
        ->get(route('subscriptions.plan.edit', ['current_team' => $this->team->slug, 'subscription' => $this->subscription]))
        ->assertConflict();

    $this->actingAs($this->user)->put(planRoute($this->subscription), ['plan' => 'starter'])
        ->assertSessionHasErrors('plan');
})->with([
    'past due' => [['status' => SubscriptionStatus::PastDue]],
    'cancelling' => [['cancelled_at' => now()]],
]);

test('unpaid upgrade invoices expire overnight but renewals do not', function () {
    $upgrade = Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'type' => InvoiceType::PlanChange]);
    $renewal = Invoice::factory()->create(['subscription_id' => $this->subscription->id]);

    $this->travelTo(now()->addDay());

    expect(app(RunBillingCycle::class)->voidStaleUpgradeInvoices())->toBe(1)
        ->and($upgrade->fresh()->status)->toBe(InvoiceStatus::Void)
        ->and($renewal->fresh()->status)->toBe(InvoiceStatus::Open);
});

test('an open upgrade invoice does not block the renewal or make hosting overdue', function () {
    Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'type' => InvoiceType::PlanChange]);
    $this->travelTo($this->subscription->current_period_ends_at->addDay());

    $cycle = app(RunBillingCycle::class);

    expect($cycle->issueRenewalInvoices())->toBe(1);
});

test('the package job moves the hestia account to the current plan', function () {
    $hestia = Mockery::mock(HestiaClient::class);
    $hestia->shouldReceive('changePackage')->once()->with($this->subscription->hestia_username, $this->business->hestia_package);
    $this->app->instance(HestiaClient::class, $hestia);

    app()->call([new ChangeHostingPackage($this->subscription), 'handle']);
});
