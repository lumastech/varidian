<?php

use App\Jobs\ProvisionHostingAccount;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\HostingAccountReady;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();

    $this->owner = User::factory()->create(['email' => 'it@acme.co.zm']);
    $this->subscription = Subscription::factory()->active()->create([
        'team_id' => $this->owner->currentTeam->id,
        'plan_id' => Plan::factory()->create(['hestia_package' => 'Starter']),
        'domain' => 'acme.co.zm',
        'hestia_username' => 'acme',
        'provisioned_at' => null,
    ]);
    $this->hestia = Mockery::mock(HestiaClient::class);
    $this->app->instance(HestiaClient::class, $this->hestia);
});

function provision(Subscription $subscription): void
{
    app()->call([new ProvisionHostingAccount($subscription), 'handle']);
}

test('it creates the account, website and ssl then emails the owner', function () {
    $this->hestia->shouldReceive('createUser')->once()->with('acme', Mockery::type('string'), 'it@acme.co.zm', 'Starter', $this->owner->currentTeam->name);
    $this->hestia->shouldReceive('addWebDomain')->once()->with('acme', 'acme.co.zm');
    $this->hestia->shouldReceive('addLetsEncrypt')->once()->with('acme', 'acme.co.zm');

    provision($this->subscription);

    expect($this->subscription->fresh()->provisioned_at)->not->toBeNull();
    Notification::assertSentTo($this->owner, HostingAccountReady::class, fn ($notification) => strlen($notification->password) === 16);
});

test('a retry after a partial run resets the password and carries on', function () {
    $this->hestia->shouldReceive('createUser')->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->with('acme')->andReturn(['CONTACT' => 'IT@acme.co.zm']);
    $this->hestia->shouldReceive('changePassword')->once()->with('acme', Mockery::type('string'));
    $this->hestia->shouldReceive('addWebDomain')->andThrow(HestiaException::commandFailed('v-add-web-domain', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('addLetsEncrypt')->andThrow(HestiaException::commandFailed('v-add-letsencrypt-domain', 15));

    provision($this->subscription);

    expect($this->subscription->fresh()->provisioned_at)->not->toBeNull();
    Notification::assertSentTo($this->owner, HostingAccountReady::class);
});

test('it never takes over a hestia account that belongs to someone else', function () {
    $this->hestia->shouldReceive('createUser')->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->andReturn(['CONTACT' => 'someone@else.com']);
    $this->hestia->shouldNotReceive('changePassword');
    $this->hestia->shouldNotReceive('addWebDomain');

    $job = (new ProvisionHostingAccount($this->subscription))->withFakeQueueInteractions();
    app()->call([$job, 'handle']);

    $job->assertFailed();
    expect($this->subscription->fresh()->provisioned_at)->toBeNull();
    Notification::assertNothingSent();
});

test('other hestia errors fail the attempt so it is retried', function () {
    $this->hestia->shouldReceive('createUser')->andThrow(HestiaException::commandFailed('v-add-user', 15));

    provision($this->subscription);
})->throws(HestiaException::class);

test('already provisioned subscriptions are skipped', function () {
    $this->subscription->update(['provisioned_at' => now()]);
    $this->hestia->shouldNotReceive('createUser');

    provision($this->subscription);

    Notification::assertNothingSent();
});

test('the ready email includes the login details', function () {
    config(['services.hestia.panel_url' => 'https://panel.test:8086']);

    $mail = (new HostingAccountReady($this->subscription, 'S3cretPass'))->toMail($this->owner);

    expect($mail->subject)->toContain('acme.co.zm')
        ->and(implode("\n", $mail->introLines))->toContain('acme')->toContain('S3cretPass')
        ->and($mail->actionUrl)->toBe('https://panel.test:8086');
});
