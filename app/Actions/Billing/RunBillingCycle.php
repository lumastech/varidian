<?php

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Jobs\IssueSslCertificate;
use App\Jobs\ProvisionHostingAccount;
use App\Jobs\SyncHostingAccountState;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Notifications\HostingSuspended;
use App\Notifications\InvoiceOverdue;
use App\Notifications\RenewalInvoiceIssued;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * The daily billing run. Every step selects by state, so running it twice in a day is harmless.
 */
class RunBillingCycle
{
    /**
     * @return array{expired: int, cancelled: int, invoiced: int, overdue: int, suspended: int, provisioned: int, ssl: int}
     */
    public function handle(): array
    {
        return [
            'expired' => $this->voidStaleUpgradeInvoices(),
            'cancelled' => $this->endCancelledSubscriptions(),
            'invoiced' => $this->issueRenewalInvoices(),
            'overdue' => $this->markOverdueSubscriptions(),
            'suspended' => $this->suspendUnpaidSubscriptions(),
            'provisioned' => $this->retryMissingProvisioning(),
            'ssl' => $this->retryMissingSslCertificates(),
        ];
    }

    /**
     * Upgrade invoices are prorated to the day they were issued, so unpaid ones expire overnight.
     */
    public function voidStaleUpgradeInvoices(): int
    {
        return Invoice::query()
            ->where('type', InvoiceType::PlanChange)
            ->where('status', InvoiceStatus::Open)
            ->where('created_at', '<', now()->startOfDay())
            ->update(['status' => InvoiceStatus::Void]);
    }

    /**
     * Stop hosting that the customer cancelled once its paid period runs out.
     */
    public function endCancelledSubscriptions(): int
    {
        return $this->each(
            Subscription::query()
                ->whereNotNull('cancelled_at')
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::PastDue, SubscriptionStatus::Suspended])
                ->where('current_period_ends_at', '<=', now()),
            function (Subscription $subscription) {
                DB::transaction(function () use ($subscription) {
                    $subscription->invoices()->where('status', InvoiceStatus::Open)->update(['status' => InvoiceStatus::Void]);
                    $subscription->update(['status' => SubscriptionStatus::Cancelled]);
                });

                SyncHostingAccountState::dispatch($subscription);
            },
        );
    }

    public function issueRenewalInvoices(): int
    {
        return $this->each(
            Subscription::query()
                ->with(['plan', 'scheduledPlan', 'team'])
                ->where('status', SubscriptionStatus::Active)
                ->whereNull('cancelled_at')
                ->where('current_period_ends_at', '<=', now()->addDays(config('billing.renewal_lead_days')))
                ->whereDoesntHave('invoices', fn ($query) => $query->where('status', InvoiceStatus::Open)->where('type', InvoiceType::Subscription)),
            function (Subscription $subscription) {
                $plan = $subscription->renewalPlan();

                $invoice = $subscription->invoices()->create([
                    'team_id' => $subscription->team_id,
                    'plan_id' => $plan->id,
                    'description' => "{$plan->name} hosting for {$subscription->domain} — 1 month from {$subscription->current_period_ends_at->toDateString()}",
                    'amount_ngwee' => $plan->price_ngwee,
                    'status' => InvoiceStatus::Open,
                    'due_at' => $subscription->current_period_ends_at,
                ]);

                $subscription->billingContact()?->notify(new RenewalInvoiceIssued($invoice));
            },
        );
    }

    public function markOverdueSubscriptions(): int
    {
        return $this->each(
            Subscription::query()
                ->with('team')
                ->where('status', SubscriptionStatus::Active)
                ->where('current_period_ends_at', '<', now())
                ->whereHas('invoices', fn ($query) => $query->where('status', InvoiceStatus::Open)->where('type', InvoiceType::Subscription)),
            function (Subscription $subscription) {
                $subscription->update(['status' => SubscriptionStatus::PastDue]);

                $invoice = $subscription->invoices()->where('status', InvoiceStatus::Open)->where('type', InvoiceType::Subscription)->latest('id')->first();

                $subscription->billingContact()?->notify(new InvoiceOverdue($invoice));
            },
        );
    }

    public function suspendUnpaidSubscriptions(): int
    {
        return $this->each(
            Subscription::query()
                ->with('team')
                ->where('status', SubscriptionStatus::PastDue)
                ->where('current_period_ends_at', '<', now()->subDays(config('billing.grace_days'))),
            function (Subscription $subscription) {
                $subscription->update(['status' => SubscriptionStatus::Suspended, 'suspended_at' => now()]);

                SyncHostingAccountState::dispatch($subscription);

                $subscription->billingContact()?->notify(new HostingSuspended($subscription));
            },
        );
    }

    /**
     * Paid hosting whose provisioning job ran out of retries (e.g. while Hestia was unreachable)
     * would otherwise never be set up, so queue it again. The job is unique per subscription.
     */
    public function retryMissingProvisioning(): int
    {
        return $this->each(
            Subscription::query()
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::PastDue])
                ->whereNull('provisioned_at'),
            fn (Subscription $subscription) => ProvisionHostingAccount::dispatch($subscription),
        );
    }

    /**
     * Let's Encrypt only succeeds once the customer's DNS points at us, so keep trying for a while.
     */
    public function retryMissingSslCertificates(): int
    {
        return $this->each(
            Subscription::query()
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::PastDue])
                ->whereNull('ssl_issued_at')
                ->where('provisioned_at', '>=', now()->subDays(config('billing.ssl_retry_days'))),
            fn (Subscription $subscription) => IssueSslCertificate::dispatch($subscription),
        );
    }

    /**
     * @param  Builder<Subscription>  $query
     * @param  callable(Subscription): mixed  $callback
     */
    private function each(Builder $query, callable $callback): int
    {
        $count = 0;

        $query->lazyById()->each(function (Subscription $subscription) use ($callback, &$count) {
            $callback($subscription);
            $count++;
        });

        return $count;
    }
}
