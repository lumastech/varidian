<?php

use App\Actions\Billing\RunBillingCycle;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->plan = Plan::factory()->create([
        'name' => 'Starter', 'slug' => 'starter', 'hestia_package' => 'Starter', 'price_ngwee' => 19900,
        'storage_gb' => 5, 'bandwidth_gb' => 20, 'websites' => 1, 'databases' => 1, 'cron_jobs' => 2, 'backups' => 3,
    ]);

    $this->hestia = Mockery::mock(HestiaClient::class);
    $this->app->instance(HestiaClient::class, $this->hestia);
});

/**
 * @return array<string, mixed>
 */
function planForm(array $overrides = []): array
{
    return [
        'name' => 'Starter', 'audience' => 'For a small business website', 'hestia_package' => 'Starter',
        'price' => '249.50', 'storage_gb' => 5, 'bandwidth_gb' => 20, 'websites' => 1, 'databases' => 1,
        'cron_jobs' => 2, 'backups' => 3, 'is_featured' => '0', 'is_active' => '1', 'sort_order' => 1,
        ...$overrides,
    ];
}

test('customers cannot manage plans', function () {
    $customer = User::factory()->create();

    $this->actingAs($customer)->get(route('admin.plans.index'))->assertForbidden();
    $this->actingAs($customer)->put(route('admin.plans.update', $this->plan), planForm())->assertForbidden();

    expect($this->plan->fresh()->price_ngwee)->toBe(19900);
});

test('admins can change a plan price and the website shows it', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.plans.update', $this->plan), planForm())
        ->assertRedirect(route('admin.plans.index'));

    expect($this->plan->fresh()->price_ngwee)->toBe(24950);

    $this->get(route('hosting'))->assertInertia(fn (AssertableInertia $page) => $page
        ->where('plans.0.price', '249.50'));
});

test('existing customers pay the new price from their next renewal', function () {
    Notification::fake();
    $subscription = Subscription::factory()->active()->create(['plan_id' => $this->plan->id, 'current_period_ends_at' => now()->addDays(3)]);
    $alreadyIssued = Invoice::factory()->create(['subscription_id' => Subscription::factory()->active()->create(['plan_id' => $this->plan->id]), 'amount_ngwee' => 19900]);

    $this->actingAs($this->admin)->put(route('admin.plans.update', $this->plan), planForm(['price' => '299']));
    app(RunBillingCycle::class)->issueRenewalInvoices();

    expect($subscription->invoices()->sole()->amount_ngwee)->toBe(29900)
        ->and($alreadyIssued->fresh()->amount_ngwee)->toBe(19900);
});

test('prices and limits are validated', function (array $overrides, string $field) {
    $this->actingAs($this->admin)
        ->put(route('admin.plans.update', $this->plan), planForm($overrides))
        ->assertSessionHasErrors($field);
})->with([
    'free' => [['price' => '0'], 'price'],
    'fractions of ngwee' => [['price' => '10.999'], 'price'],
    'no websites' => [['websites' => 0], 'websites'],
    'missing name' => [['name' => ''], 'name'],
]);

test('the slug cannot be changed once customers may have linked to it', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.plans.update', $this->plan), planForm(['slug' => 'renamed']))
        ->assertSessionHasErrors('slug');
});

test('admins can create a plan on an existing hestia package', function () {
    $this->hestia->shouldReceive('listPackages')->andReturn(['Starter' => [], 'Premium' => []]);

    $this->actingAs($this->admin)
        ->post(route('admin.plans.store'), planForm(['name' => 'Premium', 'slug' => 'premium', 'hestia_package' => 'Premium', 'price' => '1499']))
        ->assertRedirect(route('admin.plans.index'));

    expect(Plan::where('slug', 'premium')->sole())
        ->price_ngwee->toBe(149900)
        ->is_active->toBeTrue();
});

test('a plan must point at a package that exists on the server', function () {
    $this->hestia->shouldReceive('listPackages')->andReturn(['Starter' => []]);

    $this->actingAs($this->admin)
        ->put(route('admin.plans.update', $this->plan), planForm(['hestia_package' => 'Typo']))
        ->assertSessionHasErrors(['hestia_package' => 'There is no package with this name on the Hestia server.']);
});

test('prices can still be edited while hestia is unreachable', function () {
    $this->hestia->shouldReceive('listPackages')->andThrow(HestiaException::commandFailed('v-list-user-packages', 15));

    $this->actingAs($this->admin)->put(route('admin.plans.update', $this->plan), planForm())->assertSessionHasNoErrors();

    $this->actingAs($this->admin)
        ->put(route('admin.plans.update', $this->plan), planForm(['hestia_package' => 'Business']))
        ->assertSessionHasErrors('hestia_package');
});

test('taking a plan off sale hides it from the website but keeps its customers', function () {
    $subscription = Subscription::factory()->active()->create(['plan_id' => $this->plan->id]);

    $this->actingAs($this->admin)->put(route('admin.plans.update', $this->plan), planForm(['is_active' => '0']));

    $this->get(route('hosting'))->assertInertia(fn (AssertableInertia $page) => $page->has('plans', 0));
    expect($subscription->fresh()->plan_id)->toBe($this->plan->id);
});

test('the plan list flags limits that differ from the hestia package', function () {
    Subscription::factory()->active()->create(['plan_id' => $this->plan->id]);
    $this->hestia->shouldReceive('listPackages')->andReturn([
        'Starter' => ['DISK_QUOTA' => '10240', 'BANDWIDTH' => '20480', 'WEB_DOMAINS' => '1', 'DATABASES' => '1', 'CRON_JOBS' => '2', 'BACKUPS' => '3'],
    ]);
    Plan::factory()->create(['slug' => 'orphan', 'hestia_package' => 'Missing']);

    $this->actingAs($this->admin)
        ->get(route('admin.plans.index'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('admin/plans/Index')
            ->where('plans.0.customers', 1)
            ->missing('packageMismatches')
            ->loadDeferredProps(fn (AssertableInertia $reload) => $reload
                ->where('packageMismatches.starter', ['Storage gb: plan 5, server 10'])
                ->where('packageMismatches.orphan', ['Package not found on the server'])));
});
