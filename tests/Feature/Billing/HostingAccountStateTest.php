<?php

use App\Enums\SubscriptionStatus;
use App\Jobs\IssueSslCertificate;
use App\Jobs\SyncHostingAccountState;
use App\Models\Subscription;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;

beforeEach(function () {
    $this->hestia = Mockery::mock(HestiaClient::class);
    $this->app->instance(HestiaClient::class, $this->hestia);
});

function runJob(object $job): void
{
    app()->call([$job, 'handle']);
}

test('suspended and cancelled subscriptions are suspended in hestia', function (SubscriptionStatus $status) {
    $subscription = Subscription::factory()->active()->create(['status' => $status, 'hestia_username' => 'acme']);
    $this->hestia->shouldReceive('suspendUser')->once()->with('acme');

    runJob(new SyncHostingAccountState($subscription));
})->with([SubscriptionStatus::Suspended, SubscriptionStatus::Cancelled]);

test('active subscriptions are unsuspended in hestia', function () {
    $subscription = Subscription::factory()->active()->create(['hestia_username' => 'acme']);
    $this->hestia->shouldReceive('unsuspendUser')->once()->with('acme');

    runJob(new SyncHostingAccountState($subscription));
});

test('the job acts on the status at run time, not when it was queued', function () {
    $subscription = Subscription::factory()->active()->create(['status' => SubscriptionStatus::Suspended, 'hestia_username' => 'acme']);
    $job = new SyncHostingAccountState($subscription);
    $subscription->update(['status' => SubscriptionStatus::Active]);

    $this->hestia->shouldReceive('unsuspendUser')->once()->with('acme');
    $this->hestia->shouldNotReceive('suspendUser');

    runJob($job);
});

test('accounts already in the requested state are fine', function () {
    $subscription = Subscription::factory()->active()->create(['status' => SubscriptionStatus::Suspended]);
    $this->hestia->shouldReceive('suspendUser')->andThrow(HestiaException::commandFailed('v-suspend-user', HestiaException::SuspendedCode));

    runJob(new SyncHostingAccountState($subscription));
})->throwsNoExceptions();

test('other hestia errors are retried', function () {
    $subscription = Subscription::factory()->active()->create(['status' => SubscriptionStatus::Suspended]);
    $this->hestia->shouldReceive('suspendUser')->andThrow(HestiaException::commandFailed('v-suspend-user', 15));

    runJob(new SyncHostingAccountState($subscription));
})->throws(HestiaException::class);

test('unprovisioned subscriptions are left alone', function () {
    $subscription = Subscription::factory()->create(['status' => SubscriptionStatus::Cancelled]);
    $this->hestia->shouldNotReceive('suspendUser');

    runJob(new SyncHostingAccountState($subscription));
});

test('a certificate is issued and recorded', function () {
    $subscription = Subscription::factory()->active()->create(['hestia_username' => 'acme', 'domain' => 'acme.co.zm']);
    $this->hestia->shouldReceive('addLetsEncrypt')->once()->with('acme', 'acme.co.zm');

    runJob(new IssueSslCertificate($subscription));

    expect($subscription->fresh()->ssl_issued_at)->not->toBeNull();
});

test('a failed certificate attempt is left for the next run', function () {
    $subscription = Subscription::factory()->active()->create();
    $this->hestia->shouldReceive('addLetsEncrypt')->andThrow(HestiaException::commandFailed('v-add-letsencrypt-domain', 15));

    runJob(new IssueSslCertificate($subscription));

    expect($subscription->fresh()->ssl_issued_at)->toBeNull();
});
