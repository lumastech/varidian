<?php

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class CancelSubscription
{
    /**
     * Unpaid orders end now. Paid-up hosting keeps running until the end of the period
     * already paid for, then the billing cycle suspends it.
     */
    public function handle(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            $subscription->invoices()->where('status', InvoiceStatus::Open)->update(['status' => InvoiceStatus::Void]);

            $subscription->update([
                'cancelled_at' => now(),
                'status' => $subscription->status === SubscriptionStatus::Pending ? SubscriptionStatus::Cancelled : $subscription->status,
            ]);
        });
    }
}
