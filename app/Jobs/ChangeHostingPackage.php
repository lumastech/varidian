<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HostingUsage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Move the Hestia account onto the subscription's current plan's package.
 */
class ChangeHostingPackage implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    /**
     * @var list<int>
     */
    public array $backoff = [60, 300, 900, 3600];

    public function __construct(public Subscription $subscription) {}

    public function handle(HestiaClient $hestia, HostingUsage $usage): void
    {
        $subscription = $this->subscription->fresh('plan');

        if (! $subscription->provisioned_at) {
            return;
        }

        $hestia->changePackage($subscription->hestia_username, $subscription->plan->hestia_package);

        $usage->forget($subscription);
    }
}
