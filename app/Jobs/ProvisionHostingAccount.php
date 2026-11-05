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
     * How many usernames to try before giving up when the chosen one is taken.
     */
    private const int UsernameAttempts = 5;

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
        $password = Str::password(16, symbols: false);
        $username = $this->createAccount($hestia, $subscription, $owner, $password);

        if ($username === null) {
            $this->fail(new RuntimeException("No free Hestia username found for subscription {$subscription->id}."));

            return;
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
     * Create the Hestia account, or reclaim the one a previous attempt created. When the name is
     * taken by someone else (another customer, or a Linux system user such as the server admin's
     * login, which Hestia also reports as "exists"), move the subscription to a fresh name.
     *
     * @return string|null the username the account now lives under, or null if none was free
     */
    private function createAccount(HestiaClient $hestia, Subscription $subscription, User $owner, string $password): ?string
    {
        $username = $subscription->hestia_username;

        for ($attempt = 0; $attempt < self::UsernameAttempts; $attempt++) {
            try {
                $hestia->createUser($username, $password, $owner->email, $subscription->plan->hestia_package, $subscription->team->name);

                return $username;
            } catch (HestiaException $e) {
                if (! $e->alreadyExists()) {
                    throw $e;
                }
            }

            // A previous attempt created the account; reset the password so the email we send works.
            if ($this->accountBelongsTo($hestia, $username, $owner->email)) {
                $hestia->changePassword($username, $password);

                return $username;
            }

            $username = $this->alternativeUsername($username);
            $subscription->update(['hestia_username' => $username]);
        }

        return null;
    }

    /**
     * Guard against taking over an existing account that belongs to someone else. System users
     * aren't Hestia accounts, so looking them up fails; those are never ours either.
     */
    private function accountBelongsTo(HestiaClient $hestia, string $username, string $email): bool
    {
        try {
            $contact = $hestia->getUser($username)['CONTACT'] ?? '';
        } catch (HestiaException) {
            return false;
        }

        return Str::lower((string) $contact) === Str::lower($email);
    }

    private function alternativeUsername(string $taken): string
    {
        $base = preg_replace('/\d+$/', '', $taken) ?: $taken;

        do {
            $username = $base.random_int(10, 9999);
        } while (Subscription::where('hestia_username', $username)->exists());

        return $username;
    }
}
