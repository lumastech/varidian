<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

/**
 * Try once to issue the free certificate. Failure is expected while DNS still points elsewhere,
 * so it is logged rather than retried; the daily billing run tries again.
 */
class IssueSslCertificate implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(public Subscription $subscription) {}

    public function handle(HestiaClient $hestia): void
    {
        $subscription = $this->subscription->fresh();

        if ($subscription->ssl_issued_at || ! $subscription->provisioned_at) {
            return;
        }

        try {
            $hestia->addLetsEncrypt($subscription->hestia_username, $subscription->domain);
        } catch (HestiaException $e) {
            if (! $e->alreadyExists()) {
                Log::info('SSL not issued yet', ['domain' => $subscription->domain, 'code' => $e->getCode()]);

                return;
            }
        } catch (ConnectionException) {
            // Hestia may still finish issuing after we stop waiting; the next attempt sees it exists.
            Log::info('SSL request timed out', ['domain' => $subscription->domain]);

            return;
        }

        $subscription->update(['ssl_issued_at' => now()]);
    }
}
