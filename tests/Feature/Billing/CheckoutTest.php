<?php

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TeamRole;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = $this->user->currentTeam;
    $this->plan = Plan::factory()->create(['name' => 'Starter', 'slug' => 'starter', 'price_ngwee' => 19900]);
});

test('guests ordering a plan are sent to log in', function () {
    $this->get(route('hosting.order', $this->plan))->assertRedirect(route('login'));
});

test('ordering a plan redirects to checkout for the current team', function () {
    $this->actingAs($this->user)
        ->get(route('hosting.order', $this->plan))
        ->assertRedirect(route('checkout.create', ['current_team' => $this->team->slug, 'plan' => 'starter']));
});

test('checkout renders the plan', function () {
    $this->actingAs($this->user)
        ->get(route('checkout.create', ['current_team' => $this->team->slug, 'plan' => 'starter']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('billing/Checkout')
            ->where('plan.name', 'Starter')
            ->where('plan.price', '199'));
});

test('inactive plans cannot be bought', function () {
    $this->plan->update(['is_active' => false]);

    $this->actingAs($this->user)
        ->get(route('checkout.create', ['current_team' => $this->team->slug, 'plan' => 'starter']))
        ->assertNotFound();
});

test('checkout creates a pending subscription and an open invoice', function () {
    $this->actingAs($this->user)
        ->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), [
            'domain' => 'https://www.Acme.co.zm/',
        ])
        ->assertRedirect(route('invoices.show', ['current_team' => $this->team->slug, 'invoice' => Invoice::sole()]));

    $subscription = Subscription::sole();
    $invoice = Invoice::sole();

    expect($subscription)
        ->domain->toBe('acme.co.zm')
        ->hestia_username->toBe('acme')
        ->status->toBe(SubscriptionStatus::Pending)
        ->team_id->toBe($this->team->id)
        ->and($invoice)
        ->amount_ngwee->toBe(19900)
        ->status->toBe(InvoiceStatus::Open)
        ->number->toBe('INV-'.str_pad((string) $invoice->id, 6, '0', STR_PAD_LEFT));
});

test('hestia usernames are unique and never reserved', function () {
    Subscription::factory()->create(['hestia_username' => 'acme']);

    $this->actingAs($this->user)->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), ['domain' => 'acme.com']);
    $this->actingAs($this->user)->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), ['domain' => 'admin.org']);
    $this->actingAs($this->user)->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), ['domain' => '360-media.zm']);

    $usernames = Subscription::where('team_id', $this->team->id)->pluck('hestia_username');

    expect($usernames)->toHaveCount(3)
        ->and($usernames[0])->toMatch('/^acme\d+$/')
        ->and($usernames[1])->toMatch('/^admin\d+$/')
        ->and($usernames[2])->toBe('v360media');
});

test('checkout rejects invalid or already hosted domains', function (string $domain) {
    Subscription::factory()->create(['domain' => 'taken.co.zm']);

    $this->actingAs($this->user)
        ->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), ['domain' => $domain])
        ->assertSessionHasErrors('domain');

    expect(Invoice::count())->toBe(0);
})->with(['', 'not a domain', 'localhost', '-bad.com', 'taken.co.zm']);

test('team members without admin rights cannot check out', function () {
    $member = User::factory()->create();
    $this->team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($member)
        ->post(route('checkout.store', ['current_team' => $this->team->slug, 'plan' => 'starter']), ['domain' => 'acme.co.zm'])
        ->assertForbidden();
});

test('invoices of other teams are not visible', function () {
    $invoice = Invoice::factory()->create();

    $this->actingAs($this->user)
        ->get(route('invoices.show', ['current_team' => $this->team->slug, 'invoice' => $invoice]))
        ->assertNotFound();
});

test('the dashboard lists the team subscriptions and invoices', function () {
    $subscription = Subscription::factory()->active()->create(['team_id' => $this->team->id, 'plan_id' => $this->plan->id, 'domain' => 'acme.co.zm']);
    Invoice::factory()->paid()->create(['subscription_id' => $subscription->id]);
    Subscription::factory()->create();

    $this->actingAs($this->user)
        ->get(route('dashboard', ['current_team' => $this->team->slug]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard')
            ->has('subscriptions', 1)
            ->where('subscriptions.0.domain', 'acme.co.zm')
            ->where('subscriptions.0.statusLabel', 'Active')
            ->has('invoices', 1));
});
