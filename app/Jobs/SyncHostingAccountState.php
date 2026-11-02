<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Suspend or unsuspend the Hestia account to match the subscription's current status.
 * Reads the status when it runs, so out-of-order or duplicate jobs settle correctly.
 */
class SyncHostingAccountState implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    /**
     * @var list<int>
     */
    public array $backoff = [60, 300, 900, 3600];

    public function __construct(public Subscription $subscription) {}

    public function handle(HestiaClient $hestia): void
    {
        $subscription = $this->subscription->fresh();

        if (! $subscription->provisioned_at) {
            return;
        }

        try {
            $subscription->shouldBeSuspended()
                ? $hestia->suspendUser($subscription->hestia_username)
                : $hestia->unsuspendUser($subscription->hestia_username);
        } catch (HestiaException $e) {
            if (! $e->alreadyInRequestedState()) {
                throw $e;
            }
        }
    }
}
