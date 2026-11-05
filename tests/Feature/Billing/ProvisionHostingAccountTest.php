<?php

use App\Jobs\IssueSslCertificate;
use App\Jobs\ProvisionHostingAccount;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\HostingAccountReady;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

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

test('it creates the account and website, emails the owner, then queues ssl', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->once()->with('acme', Mockery::type('string'), 'it@acme.co.zm', 'Starter', $this->owner->name);
    $this->hestia->shouldReceive('listWebDomains')->once()->with('acme')->andReturn([]);
    $this->hestia->shouldReceive('addWebDomain')->once()->with('acme', 'acme.co.zm');
    $this->hestia->shouldNotReceive('addLetsEncrypt');

    provision($this->subscription);

    expect($this->subscription->fresh()->provisioned_at)->not->toBeNull();
    Notification::assertSentTo($this->owner, HostingAccountReady::class, fn ($notification) => strlen($notification->password) === 16);
    Queue::assertPushed(IssueSslCertificate::class, fn ($job) => $job->subscription->is($this->subscription));
});

test('a retry after a partial run resets the password and carries on', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->with('acme')->andReturn(['CONTACT' => 'IT@acme.co.zm']);
    $this->hestia->shouldReceive('changePassword')->once()->with('acme', Mockery::type('string'));
    $this->hestia->shouldReceive('listWebDomains')->with('acme')->andReturn(['acme.co.zm']);
    $this->hestia->shouldNotReceive('addWebDomain');

    provision($this->subscription);

    expect($this->subscription->fresh()->provisioned_at)->not->toBeNull();
    Notification::assertSentTo($this->owner, HostingAccountReady::class);
});

test('a website added by another request in the meantime is accepted', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->once();
    $this->hestia->shouldReceive('listWebDomains')->andReturn([]);
    $this->hestia->shouldReceive('addWebDomain')->andThrow(HestiaException::commandFailed('v-add-web-domain', HestiaException::ExistsCode));

    provision($this->subscription);

    expect($this->subscription->fresh()->provisioned_at)->not->toBeNull();
});

test('a failed welcome email leaves the account unprovisioned so the retry sends a new password', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->once();
    $this->hestia->shouldReceive('listWebDomains')->andReturn([]);
    $this->hestia->shouldReceive('addWebDomain')->once();
    Notification::shouldReceive('send')->andThrow(new RuntimeException('554 Recipient rejected'));

    expect(fn () => provision($this->subscription))->toThrow(RuntimeException::class);

    expect($this->subscription->fresh()->provisioned_at)->toBeNull();
    Queue::assertNotPushed(IssueSslCertificate::class);
});

test('a name owned by another customer is never taken over; a new one is used', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->with('acme', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any())
        ->once()->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->with('acme')->andReturn(['CONTACT' => 'someone@else.com']);
    $this->hestia->shouldReceive('createUser')->with(Mockery::pattern('/^acme\d{2,4}$/'), Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any())->once();
    $this->hestia->shouldNotReceive('changePassword');
    $this->hestia->shouldReceive('listWebDomains')->andReturn([]);
    $this->hestia->shouldReceive('addWebDomain')->once()->with(Mockery::pattern('/^acme\d{2,4}$/'), 'acme.co.zm');

    provision($this->subscription);

    $subscription = $this->subscription->fresh();
    expect($subscription->hestia_username)->toMatch('/^acme\d{2,4}$/')
        ->and($subscription->provisioned_at)->not->toBeNull();
    Notification::assertSentTo($this->owner, HostingAccountReady::class, fn ($notification) => $notification->subscription->hestia_username === $subscription->hestia_username);
});

test('a name held by a linux system user is replaced with a new one', function () {
    Queue::fake();
    $this->hestia->shouldReceive('createUser')->with('acme', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any())
        ->once()->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->with('acme')->andThrow(HestiaException::unexpectedResponse('v-list-user', 'HTTP 400'));
    $this->hestia->shouldReceive('createUser')->once();
    $this->hestia->shouldReceive('listWebDomains')->andReturn([]);
    $this->hestia->shouldReceive('addWebDomain')->once();

    provision($this->subscription);

    expect($this->subscription->fresh())
        ->hestia_username->not->toBe('acme')
        ->provisioned_at->not->toBeNull();
});

test('provisioning gives up when no free username can be found', function () {
    $this->hestia->shouldReceive('createUser')->times(5)->andThrow(HestiaException::commandFailed('v-add-user', HestiaException::ExistsCode));
    $this->hestia->shouldReceive('getUser')->andReturn(['CONTACT' => 'someone@else.com']);
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

test('an orphaned unique lock expires so provisioning can be queued again', function () {
    Queue::fake();
    Cache::lock('laravel_unique_job:'.ProvisionHostingAccount::class.':'.$this->subscription->id, 7200)->get();

    ProvisionHostingAccount::dispatch($this->subscription);
    Queue::assertNothingPushed();

    $this->travel(121)->minutes();

    ProvisionHostingAccount::dispatch($this->subscription);
    Queue::assertPushed(ProvisionHostingAccount::class, 1);
});
