<?php

use App\Models\Subscription;
use App\Models\User;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use App\Services\Hestia\HostingUsage;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->hestia = Mockery::mock(HestiaClient::class);
    $this->app->instance(HestiaClient::class, $this->hestia);
});

test('usage is read from hestia and cached', function () {
    $subscription = Subscription::factory()->active()->create(['hestia_username' => 'acme']);
    $this->hestia->shouldReceive('getUser')->once()->with('acme')->andReturn([
        'U_DISK' => '1200', 'DISK_QUOTA' => '5120',
        'U_BANDWIDTH' => '300', 'BANDWIDTH' => 'unlimited',
        'U_WEB_DOMAINS' => '1', 'WEB_DOMAINS' => '1',
        'U_DATABASES' => '0', 'DATABASES' => '1',
    ]);

    $usage = app(HostingUsage::class)->for($subscription);
    app(HostingUsage::class)->for($subscription);

    expect($usage)->toBe([
        'disk' => ['used' => 1200, 'limit' => 5120],
        'bandwidth' => ['used' => 300, 'limit' => null],
        'websites' => ['used' => 1, 'limit' => 1],
        'databases' => ['used' => 0, 'limit' => 1],
    ]);
});

test('usage is null when hestia is unavailable or the account is not set up', function () {
    $this->hestia->shouldReceive('getUser')->andThrow(HestiaException::commandFailed('v-list-user', 15));

    expect(app(HostingUsage::class)->for(Subscription::factory()->active()->create()))->toBeNull()
        ->and(app(HostingUsage::class)->for(Subscription::factory()->create()))->toBeNull();
});

test('the dashboard loads usage as a deferred prop', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->active()->create(['team_id' => $user->currentTeam->id]);
    $this->hestia->shouldReceive('getUser')->andReturn(['U_DISK' => '10', 'DISK_QUOTA' => '100']);

    $this->actingAs($user)
        ->get(route('dashboard', ['current_team' => $user->currentTeam->slug]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->missing('usage')
            ->loadDeferredProps(fn (AssertableInertia $reload) => $reload
                ->where("usage.{$subscription->id}.disk.used", 10)));
});
