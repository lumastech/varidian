<?php

namespace App\Actions\Billing;

use App\Actions\Partners\RecordHostingCommission;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Jobs\ChangeHostingPackage;
use App\Jobs\ProvisionHostingAccount;
use App\Jobs\SyncHostingAccountState;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class MarkInvoicePaid
{
    public function __construct(private RecordHostingCommission $recordHostingCommission) {}

    /**
     * Settle the invoice and bring its subscription up to date. Call inside the payment's transaction.
     */
    public function handle(Invoice $invoice): void
    {
        if ($invoice->status === InvoiceStatus::Paid) {
            Log::warning('Invoice paid more than once; refund the duplicate payment', ['invoice' => $invoice->number]);

            return;
        }

        $invoice->update(['status' => InvoiceStatus::Paid, 'paid_at' => now()]);

        $this->recordHostingCommission->handle($invoice);

        $subscription = $invoice->subscription;

        if (! $subscription) {
            return;
        }

        if ($subscription->status === SubscriptionStatus::Cancelled) {
            Log::warning('Payment received for a cancelled subscription; refund or reinstate it', ['invoice' => $invoice->number]);

            return;
        }

        if ($invoice->type === InvoiceType::PlanChange) {
            $this->switchPlan($subscription, $invoice);

            return;
        }

        $wasSuspended = $subscription->status === SubscriptionStatus::Suspended;

        // An overdue customer kept their service, so the period runs on from the due date;
        // a suspended one didn't, so their new month starts today.
        $periodStart = $wasSuspended || ! $subscription->current_period_ends_at ? now() : $subscription->current_period_ends_at;

        $subscription->update([
            'status' => SubscriptionStatus::Active,
            'current_period_starts_at' => $periodStart,
            'current_period_ends_at' => $periodStart->addMonthNoOverflow(),
            'suspended_at' => null,
        ]);

        if (! $subscription->provisioned_at) {
            ProvisionHostingAccount::dispatch($subscription)->afterCommit();
        } elseif ($wasSuspended) {
            SyncHostingAccountState::dispatch($subscription)->afterCommit();
        }

        // A renewal issued after a downgrade was scheduled carries the new plan.
        if ($invoice->plan_id && $invoice->plan_id !== $subscription->plan_id) {
            $this->switchPlan($subscription, $invoice);
        }
    }

    private function switchPlan(Subscription $subscription, Invoice $invoice): void
    {
        $subscription->update(['plan_id' => $invoice->plan_id, 'scheduled_plan_id' => null]);
        $subscription->repriceOpenRenewal($invoice->plan);

        ChangeHostingPackage::dispatch($subscription)->afterCommit();
    }
}
