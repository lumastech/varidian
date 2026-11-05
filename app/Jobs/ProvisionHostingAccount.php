<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use App\Notifications\HostingAccountReady;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * Create the customer's Hestia account and website. Every step tolerates having already
 * run, so a retry after a partial failure picks up where the last attempt stopped.
 */
class ProvisionHostingAccount implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    /**
     * @var list<int>
     */
    public array $backoff = [60, 300, 900, 3600];

    /**
     * Outlast every retry (~81 minutes of backoff), but let a lock orphaned by a cleared
     * queue expire so the billing run can queue provisioning again.
     */
    public int $uniqueFor = 7200;

    public function __construct(public Subscription $subscription) {}

    public function uniqueId(): string
    {
        return (string) $this->subscription->id;
    }

    public function handle(HestiaClient $hestia): void
    {
        $subscription = $this->subscription->fresh(['plan', 'team']);

        if ($subscription->provisioned_at) {
            return;
        }

        /** @var User $owner */
        $owner = $subscription->team->owner();
        $username = $subscription->hestia_username;
        $password = Str::password(16, symbols: false);

        try {
            $hestia->createUser($username, $password, $owner->email, $subscription->plan->hestia_package, $subscription->team->name);
        } catch (HestiaException $e) {
            if (! $e->alreadyExists()) {
                throw $e;
            }

            // A previous attempt created the account; reset the password so the email we send works.
            if (! $this->accountBelongsTo($hestia, $username, $owner->email)) {
                $this->fail(new RuntimeException("Hestia user {$username} already exists and belongs to another contact."));

                return;
            }

            $hestia->changePassword($username, $password);
        }

        // Hestia checks the package's domain limit before whether the domain exists, so a retry
        // would fail with a limit error rather than "exists"; look before adding instead.
        if (! in_array($subscription->domain, $hestia->listWebDomains($username), true)) {
            try {
                $hestia->addWebDomain($username, $subscription->domain);
            } catch (HestiaException $e) {
                if (! $e->alreadyExists()) {
                    throw $e;
                }
            }
        }

        // Mail first: if it fails, the retry resets the password and sends a new one, whereas
        // marking the account provisioned first would lose the only copy of the password.
        $owner->notify(new HostingAccountReady($subscription, $password));

        $subscription->update(['provisioned_at' => now()]);

        // Let's Encrypt can outlast the HTTP timeout and usually fails until the domain points
        // at us, so it runs on its own; the daily billing run keeps retrying.
        IssueSslCertificate::dispatch($subscription);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Hosting provisioning failed', [
            'subscription' => $this->subscription->id,
            'error' => $exception?->getMessage(),
        ]);
    }

    /**
     * Guard against taking over an existing Hestia account that belongs to someone else.
     */
    private function accountBelongsTo(HestiaClient $hestia, string $username, string $email): bool
    {
        return Str::lower((string) ($hestia->getUser($username)['CONTACT'] ?? '')) === Str::lower($email);
    }
}
