<?php

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Hestia\HostingUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChangePlan
{
    /**
     * Lenco won't collect less than this, so tiny prorations round up to it.
     */
    private const int MinimumChargeNgwee = 100;

    public function __construct(private HostingUsage $usage) {}

    /**
     * Upgrades return an invoice for the prorated difference and apply once it is paid.
     * Downgrades are scheduled for the next renewal and return null. Choosing the current
     * plan withdraws a scheduled downgrade.
     *
     * @throws ValidationException
     */
    public function handle(Subscription $subscription, Plan $plan): ?Invoice
    {
        if (! $this->canChange($subscription) || ! $plan->is_active) {
            throw ValidationException::withMessages(['plan' => 'This plan change is not available.']);
        }

        if ($plan->is($subscription->plan)) {
            $this->schedule($subscription, null);

            return null;
        }

        if ($plan->price_ngwee > $subscription->plan->price_ngwee) {
            return $this->upgrade($subscription, $plan);
        }

        $this->ensureUsageFits($subscription, $plan);
        $this->schedule($subscription, $plan);

        return null;
    }

    public function canChange(Subscription $subscription): bool
    {
        return $subscription->status === SubscriptionStatus::Active
            && ! $subscription->isCancelling()
            && $subscription->current_period_ends_at?->isFuture();
    }

    /**
     * Charge only for the part of the current period that is left.
     */
    public function upgradeCost(Subscription $subscription, Plan $plan): int
    {
        $endsAt = $subscription->current_period_ends_at;
        $startsAt = $subscription->current_period_starts_at ?? $endsAt->subMonthNoOverflow();
        $periodSeconds = max(1, $startsAt->diffInSeconds($endsAt));
        $remaining = max(0, min(1, now()->diffInSeconds($endsAt) / $periodSeconds));

        return max(self::MinimumChargeNgwee, (int) ceil(($plan->price_ngwee - $subscription->plan->price_ngwee) * $remaining));
    }

    private function upgrade(Subscription $subscription, Plan $plan): Invoice
    {
        return DB::transaction(function () use ($subscription, $plan) {
            $subscription->invoices()
                ->where('type', InvoiceType::PlanChange)
                ->where('status', InvoiceStatus::Open)
                ->update(['status' => InvoiceStatus::Void]);

            return $subscription->invoices()->create([
                'team_id' => $subscription->team_id,
                'type' => InvoiceType::PlanChange,
                'plan_id' => $plan->id,
                'description' => "Upgrade {$subscription->domain} to {$plan->name} until {$subscription->current_period_ends_at->toDateString()}",
                'amount_ngwee' => $this->upgradeCost($subscription, $plan),
                'status' => InvoiceStatus::Open,
                'due_at' => now(),
            ]);
        });
    }

    private function schedule(Subscription $subscription, ?Plan $plan): void
    {
        DB::transaction(function () use ($subscription, $plan) {
            $subscription->update(['scheduled_plan_id' => $plan?->id]);
            $subscription->repriceOpenRenewal($plan ?? $subscription->plan);
        });
    }

    /**
     * @throws ValidationException
     */
    private function ensureUsageFits(Subscription $subscription, Plan $plan): void
    {
        if (! $subscription->provisioned_at) {
            return;
        }

        $usage = $this->usage->for($subscription);

        if ($usage === null) {
            throw ValidationException::withMessages(['plan' => "We couldn't check your current usage. Please try again shortly."]);
        }

        $problems = array_filter([
            $usage['disk']['used'] > $plan->storage_gb * 1024 ? "you're using more than {$plan->storage_gb}GB of storage" : null,
            $usage['websites']['used'] > $plan->websites ? "you have more than {$plan->websites} websites" : null,
            $usage['databases']['used'] > $plan->databases ? "you have more than {$plan->databases} databases" : null,
        ]);

        if ($problems) {
            throw ValidationException::withMessages([
                'plan' => "You can't move to {$plan->name} yet: ".implode(', ', $problems).'.',
            ]);
        }
    }
}
