<?php

use App\Actions\Billing\MarkInvoicePaid;
use App\Enums\CommissionType;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\Payout;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\User;
use App\Notifications\CommissionPaid;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    Queue::fake();
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->partner = Partner::factory()->create();
    $this->team = Team::factory()->create();
    $this->subscription = Subscription::factory()->create(['team_id' => $this->team->id]);
});

function payHostingInvoice(Subscription $subscription, int $amountNgwee = 19900): Invoice
{
    $invoice = Invoice::factory()->create(['subscription_id' => $subscription->id, 'amount_ngwee' => $amountNgwee]);

    app(MarkInvoicePaid::class)->handle($invoice);

    return $invoice->fresh();
}

test('partners earn 15% of hosting their client pays for', function () {
    $lead = Lead::factory()->for($this->partner)->won($this->team)->create();

    $invoice = payHostingInvoice($this->subscription);

    expect(Commission::sole())
        ->partner_id->toBe($this->partner->id)
        ->lead_id->toBe($lead->id)
        ->invoice_id->toBe($invoice->id)
        ->type->toBe(CommissionType::Hosting)
        ->base_ngwee->toBe(19900)
        ->rate_percent->toBe(15)
        ->amount_ngwee->toBe(2985);
});

test('hosting commission stops after the first 12 months', function () {
    Lead::factory()->for($this->partner)->won($this->team)->create();

    payHostingInvoice($this->subscription);
    $this->travel(11)->months();
    payHostingInvoice($this->subscription);
    $this->travel(2)->months();
    payHostingInvoice($this->subscription);

    expect(Commission::count())->toBe(2);
});

test('clients nobody referred earn no commission', function () {
    Lead::factory()->for($this->partner)->confirmed()->create();

    payHostingInvoice($this->subscription);

    expect(Commission::count())->toBe(0);
});

test('marking a lead won credits hosting the client already paid for', function () {
    $lead = Lead::factory()->for($this->partner)->confirmed()->create();
    $this->travel(1)->day();
    payHostingInvoice($this->subscription);

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'won', 'team_id' => $this->team->id]);
    $this->actingAs($this->admin)->put(route('admin.leads.update', Lead::factory()->for($this->partner)->confirmed()->create()), ['decision' => 'won', 'team_id' => $this->team->id]);

    expect(Commission::sole()->lead_id)->toBe($lead->id);
});

test('ended partners are still paid on clients they signed before', function () {
    Lead::factory()->for($this->partner)->won($this->team)->create();
    $this->partner->update(['status' => 'terminated', 'terminated_at' => now()->addDay()]);
    $this->travel(2)->days();

    payHostingInvoice($this->subscription);

    expect(Commission::count())->toBe(1);
});

test('admins record commission on other products at the programme rate', function (string $type, int $expected) {
    $lead = Lead::factory()->for($this->partner)->won($this->team)->create();

    $this->actingAs($this->admin)
        ->post(route('admin.partners.commissions.store', $this->partner), [
            'type' => $type,
            'lead_id' => $lead->id,
            'description' => 'Client payment',
            'amount' => '1000',
            'earned_on' => now()->toDateString(),
        ])
        ->assertSessionHasNoErrors();

    expect(Commission::sole()->amount_ngwee)->toBe($expected);
})->with([
    'saas subscription' => ['subscription', 20000],
    'licence' => ['licence', 15000],
    'custom project instalment' => ['project', 10000],
    'referral' => ['referral', 5000],
]);

test('refunds are deducted as negative adjustments', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.partners.commissions.store', $this->partner), [
            'type' => 'adjustment',
            'description' => 'Refund on INV-000012',
            'amount' => '-29.85',
            'earned_on' => now()->toDateString(),
        ])
        ->assertSessionHasNoErrors();

    expect(Commission::sole())->amount_ngwee->toBe(-2985)->rate_percent->toBeNull();
});

test('manual commission is validated', function (array $overrides, string $field) {
    $lead = Lead::factory()->for($this->partner)->won($this->team)->create();

    $this->actingAs($this->admin)
        ->post(route('admin.partners.commissions.store', $this->partner), [
            'type' => 'licence', 'lead_id' => $lead->id, 'description' => 'Licence', 'amount' => '100', 'earned_on' => now()->toDateString(),
            ...$overrides,
        ])
        ->assertSessionHasErrors($field);
})->with([
    'hosting is automatic' => [['type' => 'hosting'], 'type'],
    'negative payment' => [['amount' => '-5'], 'amount'],
    'future date' => [['earned_on' => now()->addDay()->toDateString()], 'earned_on'],
]);

test('commission can only be credited against the partner\'s own leads', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.partners.commissions.store', $this->partner), [
            'type' => 'licence', 'lead_id' => Lead::factory()->create()->id, 'description' => 'Licence', 'amount' => '100', 'earned_on' => now()->toDateString(),
        ])
        ->assertSessionHasErrors('lead_id');
});

test('payouts pay everything earned before this month and send a statement', function () {
    Notification::fake();
    Commission::factory()->for($this->partner)->create(['amount_ngwee' => 75000, 'earned_at' => now()->subMonthNoOverflow()->startOfMonth()]);
    Commission::factory()->for($this->partner)->create(['amount_ngwee' => -5000, 'earned_at' => now()->subMonthNoOverflow()->startOfMonth()]);
    $thisMonth = Commission::factory()->for($this->partner)->create(['earned_at' => now()->startOfMonth()]);

    $this->actingAs($this->admin)
        ->post(route('admin.partners.payouts.store', $this->partner), ['method' => 'mobile-money', 'reference' => 'MP2611.1234'])
        ->assertSessionHasNoErrors();

    $payout = Payout::sole();

    expect($payout)->amount_ngwee->toBe(70000)->recorded_by->toBe($this->admin->id)
        ->and($payout->commissions()->count())->toBe(2)
        ->and($thisMonth->fresh()->payout_id)->toBeNull();

    Notification::assertSentTo($this->partner->user, CommissionPaid::class);

    $this->actingAs($this->partner->user)
        ->get(route('partner.payouts.show', $payout))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('partners/Statement')
            ->where('payout.amount', '700.00')
            ->has('commissions', 2));
});

test('nothing is paid out when refunds outweigh earnings', function () {
    Commission::factory()->for($this->partner)->create(['amount_ngwee' => -5000]);

    $this->actingAs($this->admin)
        ->post(route('admin.partners.payouts.store', $this->partner), ['method' => 'mobile-money'])
        ->assertSessionHasErrors('payout');

    expect(Payout::count())->toBe(0);
});

test('partners cannot see other partners\' statements', function () {
    $payout = Payout::factory()->create();

    $this->actingAs($this->partner->user)->get(route('partner.payouts.show', $payout))->assertNotFound();
});

test('admins see a partner\'s leads, commission and balance due', function () {
    Commission::factory()->for($this->partner)->create(['amount_ngwee' => 75000]);

    $this->actingAs($this->admin)
        ->get(route('admin.partners.show', $this->partner))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('admin/partners/Show')
            ->where('partner.due', '750.00')
            ->has('commissions', 1));
});
