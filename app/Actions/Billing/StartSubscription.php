<?php

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StartSubscription
{
    /**
     * Usernames that collide with system accounts on the Hestia server.
     *
     * @var list<string>
     */
    private const array ReservedUsernames = [
        'admin', 'root', 'hestia', 'www', 'www-data', 'mail', 'ftp', 'nobody', 'backup', 'bin', 'daemon',
        'sys', 'sync', 'nginx', 'apache', 'mysql', 'postgres', 'dovecot', 'exim', 'bind', 'test',
    ];

    /**
     * Create a pending subscription and the invoice that activates it.
     */
    public function handle(Team $team, Plan $plan, string $domain): Invoice
    {
        $domain = Str::lower($domain);

        return DB::transaction(function () use ($team, $plan, $domain) {
            $subscription = $team->subscriptions()->create([
                'plan_id' => $plan->id,
                'domain' => $domain,
                'hestia_username' => $this->uniqueUsername($domain),
                'status' => SubscriptionStatus::Pending,
            ]);

            return $subscription->invoices()->create([
                'team_id' => $team->id,
                'description' => "{$plan->name} hosting for {$domain} — 1 month",
                'amount_ngwee' => $plan->price_ngwee,
                'status' => InvoiceStatus::Open,
                'due_at' => now(),
            ]);
        });
    }

    /**
     * Derive a Hestia username from the domain's first label, e.g. "acme" for acme.co.zm.
     */
    private function uniqueUsername(string $domain): string
    {
        $base = Str::of($domain)->before('.')->replaceMatches('/[^a-z0-9]/', '')->limit(12, '')->toString();

        if (! preg_match('/^[a-z]/', $base)) {
            $base = 'v'.$base;
        }

        $base = str_pad($base, 3, '0');
        $username = $base;

        while (in_array($username, self::ReservedUsernames, true) || Subscription::where('hestia_username', $username)->exists()) {
            $username = $base.random_int(10, 9999);
        }

        return $username;
    }
}
