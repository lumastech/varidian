<?php

use App\Enums\PartnerStatus;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

/**
 * @return array<string, mixed>
 */
function partnerApplication(array $overrides = []): array
{
    return [
        'full_name' => 'Chanda Mwila',
        'nrc_number' => '123456/10/1',
        'phone' => '+260971000000',
        'payout_method' => 'mobile-money',
        'payout_details' => 'Airtel Money 0971000000',
        'accept_agreement' => '1',
        ...$overrides,
    ];
}

test('anyone can read about the programme', function () {
    $this->get(route('partners'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('marketing/Partners')
            ->where('rates.subscription', 20)
            ->where('leadOwnershipDays', 90));
});

test('people without a partner profile are sent to apply', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('partner.dashboard'))
        ->assertRedirect(route('partner.apply'));
});

test('signing the agreement creates a pending application', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('partner.apply.store'), partnerApplication())
        ->assertRedirect(route('partner.dashboard'));

    expect($user->partner)
        ->status->toBe(PartnerStatus::Pending)
        ->nrc_number->toBe('123456/10/1')
        ->agreement_accepted_at->not->toBeNull();

    $this->actingAs($user)
        ->get(route('partner.dashboard'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('partners/Dashboard')
            ->where('partner.status', 'pending'));
});

test('applications are validated', function (array $overrides, string $field) {
    $this->actingAs(User::factory()->create())
        ->post(route('partner.apply.store'), partnerApplication($overrides))
        ->assertSessionHasErrors($field);
})->with([
    'agreement not accepted' => [['accept_agreement' => '0'], 'accept_agreement'],
    'malformed NRC' => [['nrc_number' => '12345'], 'nrc_number'],
    'unknown payout method' => [['payout_method' => 'crypto'], 'payout_method'],
]);

test('a user can only apply once', function () {
    $partner = Partner::factory()->create();

    $this->actingAs($partner->user)
        ->post(route('partner.apply.store'), partnerApplication())
        ->assertConflict();
});

test('pending and ended partners cannot register leads', function (string $state) {
    $partner = Partner::factory()->{$state}()->create();

    $this->actingAs($partner->user)
        ->post(route('partner.leads.store'), ['organisation' => 'Acme', 'contact_name' => 'A', 'contact_phone' => '1', 'interest' => 'HostelHub'])
        ->assertForbidden();

    expect(Lead::count())->toBe(0);
})->with(['pending', 'terminated']);

test('admins approve applications and can end agreements', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $partner = Partner::factory()->pending()->create();

    $this->actingAs($admin)
        ->put(route('admin.partners.status.update', $partner), ['status' => 'active'])
        ->assertRedirect();

    expect($partner->fresh())->status->toBe(PartnerStatus::Active)->approved_at->not->toBeNull();

    $this->actingAs($admin)->put(route('admin.partners.status.update', $partner), ['status' => 'terminated']);

    expect($partner->fresh())->status->toBe(PartnerStatus::Terminated)->terminated_at->not->toBeNull();
});

test('customers cannot manage partners', function () {
    $partner = Partner::factory()->pending()->create();
    $customer = User::factory()->create();

    $this->actingAs($customer)->get(route('admin.partners.index'))->assertForbidden();
    $this->actingAs($customer)->put(route('admin.partners.status.update', $partner), ['status' => 'active'])->assertForbidden();
});
