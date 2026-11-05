<?php

use App\Actions\Billing\RunBillingCycle;
use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Jobs\IssueSslCertificate;
use App\Jobs\ProvisionHostingAccount;
use App\Jobs\SyncHostingAccountState;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\HostingSuspended;
use App\Notifications\InvoiceOverdue;
use App\Notifications\RenewalInvoiceIssued;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Notification::fake();
    Queue::fake();
    config(['billing.renewal_lead_days' => 7, 'billing.grace_days' => 7]);

    $this->owner = User::factory()->create();
    $this->plan = Plan::factory()->create(['name' => 'Business', 'price_ngwee' => 49900]);
});

function subscriptionEndingIn(int $days, array $attributes = []): Subscription
{
    return Subscription::factory()->active()->create([
        'team_id' => test()->owner->currentTeam->id,
        'plan_id' => test()->plan->id,
        'current_period_ends_at' => now()->addDays($days),
        'ssl_issued_at' => now(),
        ...$attributes,
    ]);
}

test('renewal invoices are issued a week before the period ends', function () {
    $due = subscriptionEndingIn(5);
    $notYet = subscriptionEndingIn(20);

    expect(app(RunBillingCycle::class)->issueRenewalInvoices())->toBe(1);

    $invoice = $due->invoices()->sole();
    expect($invoice)
        ->amount_ngwee->toBe(49900)
        ->status->toBe(InvoiceStatus::Open)
        ->due_at->toDateString()->toBe($due->current_period_ends_at->toDateString())
        ->and($notYet->invoices()->count())->toBe(0);

    Notification::assertSentTo($this->owner, RenewalInvoiceIssued::class, fn ($notification) => $notification->invoice->is($invoice));
});

test('running the cycle twice does not issue a second invoice', function () {
    subscriptionEndingIn(5);

    app(RunBillingCycle::class)->handle();
    app(RunBillingCycle::class)->handle();

    expect(Invoice::count())->toBe(1);
    Notification::assertSentToTimes($this->owner, RenewalInvoiceIssued::class, 1);
});

test('subscriptions being cancelled are not invoiced', function () {
    subscriptionEndingIn(5, ['cancelled_at' => now()]);

    expect(app(RunBillingCycle::class)->issueRenewalInvoices())->toBe(0);
});

test('unpaid subscriptions past their period become overdue and the owner is told once', function () {
    $subscription = subscriptionEndingIn(-1);
    Invoice::factory()->create(['subscription_id' => $subscription->id]);

    app(RunBillingCycle::class)->handle();
    app(RunBillingCycle::class)->handle();

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::PastDue);
    Notification::assertSentToTimes($this->owner, InvoiceOverdue::class, 1);
    Queue::assertNotPushed(SyncHostingAccountState::class);
});

test('overdue subscriptions are suspended after the grace period', function () {
    $withinGrace = subscriptionEndingIn(-3, ['status' => SubscriptionStatus::PastDue]);
    $pastGrace = subscriptionEndingIn(-8, ['status' => SubscriptionStatus::PastDue]);

    expect(app(RunBillingCycle::class)->suspendUnpaidSubscriptions())->toBe(1);

    expect($withinGrace->fresh()->status)->toBe(SubscriptionStatus::PastDue)
        ->and($pastGrace->fresh())
        ->status->toBe(SubscriptionStatus::Suspended)
        ->suspended_at->not->toBeNull();

    Queue::assertPushed(SyncHostingAccountState::class, fn ($job) => $job->subscription->is($pastGrace));
    Notification::assertSentTo($this->owner, HostingSuspended::class);
});

test('cancelled subscriptions end when their paid period runs out', function () {
    $ended = subscriptionEndingIn(-1, ['cancelled_at' => now()->subWeek()]);
    $stillPaid = subscriptionEndingIn(10, ['cancelled_at' => now()->subWeek()]);
    $openInvoice = Invoice::factory()->create(['subscription_id' => $ended->id]);

    expect(app(RunBillingCycle::class)->endCancelledSubscriptions())->toBe(1);

    expect($ended->fresh()->status)->toBe(SubscriptionStatus::Cancelled)
        ->and($stillPaid->fresh()->status)->toBe(SubscriptionStatus::Active)
        ->and($openInvoice->fresh()->status)->toBe(InvoiceStatus::Void);

    Queue::assertPushed(SyncHostingAccountState::class, fn ($job) => $job->subscription->is($ended));
});

test('missing ssl certificates are retried for recently provisioned hosting', function () {
    $needsSsl = subscriptionEndingIn(20, ['ssl_issued_at' => null]);
    subscriptionEndingIn(20, ['ssl_issued_at' => null, 'provisioned_at' => now()->subDays(60)]);
    subscriptionEndingIn(20);

    expect(app(RunBillingCycle::class)->retryMissingSslCertificates())->toBe(1);

    Queue::assertPushed(IssueSslCertificate::class, fn ($job) => $job->subscription->is($needsSsl));
});

test('paid hosting that was never provisioned is queued for provisioning again', function () {
    $stuck = subscriptionEndingIn(20, ['provisioned_at' => null]);
    $stuckOverdue = subscriptionEndingIn(-1, ['provisioned_at' => null, 'status' => SubscriptionStatus::PastDue]);
    subscriptionEndingIn(20, ['provisioned_at' => null, 'status' => SubscriptionStatus::Pending]);
    subscriptionEndingIn(20, ['provisioned_at' => null, 'status' => SubscriptionStatus::Cancelled]);
    subscriptionEndingIn(20);

    expect(app(RunBillingCycle::class)->retryMissingProvisioning())->toBe(2);

    Queue::assertPushed(ProvisionHostingAccount::class, 2);
    Queue::assertPushed(ProvisionHostingAccount::class, fn ($job) => $job->subscription->is($stuck));
    Queue::assertPushed(ProvisionHostingAccount::class, fn ($job) => $job->subscription->is($stuckOverdue));
});

test('the command runs the whole cycle', function () {
    subscriptionEndingIn(5);

    $this->artisan('billing:run')
        ->expectsTable(['Step', 'Subscriptions'], [
            ['expired', 0], ['cancelled', 0], ['invoiced', 1], ['overdue', 0], ['suspended', 0], ['provisioned', 0], ['ssl', 0],
        ])
        ->assertSuccessful();
});
