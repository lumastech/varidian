<?php

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TeamRole;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = $this->user->currentTeam;
});

function cancelRoute(Subscription $subscription): string
{
    return route('subscriptions.cancel', ['current_team' => $subscription->team->slug, 'subscription' => $subscription]);
}

test('cancelling an unpaid order ends it immediately and voids its invoice', function () {
    $subscription = Subscription::factory()->create(['team_id' => $this->team->id]);
    $invoice = Invoice::factory()->create(['subscription_id' => $subscription->id]);

    $this->actingAs($this->user)
        ->post(cancelRoute($subscription))
        ->assertRedirect(route('dashboard', ['current_team' => $this->team->slug]));

    expect($subscription->fresh())
        ->status->toBe(SubscriptionStatus::Cancelled)
        ->cancelled_at->not->toBeNull()
        ->and($invoice->fresh()->status)->toBe(InvoiceStatus::Void);
});

test('cancelling active hosting keeps it running until the period ends', function () {
    $subscription = Subscription::factory()->active()->create(['team_id' => $this->team->id]);
    $renewal = Invoice::factory()->create(['subscription_id' => $subscription->id]);

    $this->actingAs($this->user)->post(cancelRoute($subscription));

    expect($subscription->fresh())
        ->status->toBe(SubscriptionStatus::Active)
        ->isCancelling()->toBeTrue()
        ->and($renewal->fresh()->status)->toBe(InvoiceStatus::Void);
});

test('subscriptions cannot be cancelled twice', function () {
    $subscription = Subscription::factory()->active()->create(['team_id' => $this->team->id, 'cancelled_at' => now()]);

    $this->actingAs($this->user)->post(cancelRoute($subscription))->assertConflict();
});

test('members without admin rights cannot cancel', function () {
    $member = User::factory()->create();
    $this->team->members()->attach($member, ['role' => TeamRole::Member->value]);
    $subscription = Subscription::factory()->active()->create(['team_id' => $this->team->id]);

    $this->actingAs($member)->post(cancelRoute($subscription))->assertForbidden();
});

test('other teams subscriptions cannot be cancelled', function () {
    $subscription = Subscription::factory()->active()->create();

    $this->actingAs($this->user)
        ->post(route('subscriptions.cancel', ['current_team' => $this->team->slug, 'subscription' => $subscription]))
        ->assertNotFound();

    expect($subscription->fresh()->cancelled_at)->toBeNull();
});
