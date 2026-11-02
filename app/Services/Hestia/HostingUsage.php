<?php

namespace App\Services\Hestia;

use App\Models\Subscription;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Disk, bandwidth and object counts for a hosting account, read from `v-list-user`.
 * Hestia reports sizes in MB and "unlimited" for uncapped limits.
 */
class HostingUsage
{
    public function __construct(private HestiaClient $hestia) {}

    /**
     * @return array{disk: array{used: int, limit: ?int}, bandwidth: array{used: int, limit: ?int}, websites: array{used: int, limit: ?int}, databases: array{used: int, limit: ?int}}|null
     */
    public function for(Subscription $subscription): ?array
    {
        if (! $subscription->provisioned_at) {
            return null;
        }

        return Cache::remember("hosting-usage:{$subscription->id}", now()->addMinutes(10), function () use ($subscription) {
            try {
                $user = $this->hestia->getUser($subscription->hestia_username);
            } catch (HestiaException|HttpClientException $e) {
                Log::warning('Could not read hosting usage', ['subscription' => $subscription->id, 'error' => $e->getMessage()]);

                return null;
            }

            return [
                'disk' => $this->metric($user, 'U_DISK', 'DISK_QUOTA'),
                'bandwidth' => $this->metric($user, 'U_BANDWIDTH', 'BANDWIDTH'),
                'websites' => $this->metric($user, 'U_WEB_DOMAINS', 'WEB_DOMAINS'),
                'databases' => $this->metric($user, 'U_DATABASES', 'DATABASES'),
            ];
        });
    }

    /**
     * Forget the cached figures, e.g. after a package change.
     */
    public function forget(Subscription $subscription): void
    {
        Cache::forget("hosting-usage:{$subscription->id}");
    }

    /**
     * @param  array<string, string>  $user
     * @return array{used: int, limit: ?int}
     */
    private function metric(array $user, string $usedKey, string $limitKey): array
    {
        $limit = $user[$limitKey] ?? null;

        return [
            'used' => (int) ($user[$usedKey] ?? 0),
            'limit' => is_numeric($limit) ? (int) $limit : null,
        ];
    }
}
