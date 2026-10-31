<?php

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Jobs\ProvisionHostingAccount;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Lenco\LencoClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmPayment
{
    public function __construct(private LencoClient $lenco) {}

    /**
     * Ask Lenco for the payment's outcome and apply it. Safe to call repeatedly, from the
     * browser callback and the webhook alike: a payment is only ever settled once.
     */
    public function handle(Payment $payment): Payment
    {
        if ($payment->status->isFinal()) {
            return $payment;
        }

        $collection = $this->lenco->collectionStatus($payment->reference);

        return DB::transaction(function () use ($payment, $collection) {
            $payment = Payment::query()->lockForUpdate()->findOrFail($payment->id);

            if ($payment->status->isFinal()) {
                return $payment;
            }

            $status = PaymentStatus::fromLenco($collection['status'] ?? null);
            $failureReason = $collection['reasonForFailure'] ?? null;

            if ($status === PaymentStatus::Successful && ! $this->matchesPayment($payment, $collection)) {
                Log::error('Lenco collection does not match payment', ['reference' => $payment->reference, 'collection' => $collection]);

                $status = PaymentStatus::Failed;
                $failureReason = 'The amount paid did not match the invoice.';
            }

            $payment->update([
                'status' => $status,
                'lenco_reference' => $collection['lencoReference'] ?? null,
                'channel' => $collection['type'] ?? null,
                'failure_reason' => $status === PaymentStatus::Failed ? $failureReason : null,
                'provider_response' => $collection,
                'paid_at' => $status === PaymentStatus::Successful ? now() : null,
            ]);

            if ($status === PaymentStatus::Successful) {
                $this->markInvoicePaid($payment->invoice);
            }

            return $payment;
        });
    }

    /**
     * @param  array<string, mixed>  $collection
     */
    private function matchesPayment(Payment $payment, array $collection): bool
    {
        return ($collection['currency'] ?? null) === 'ZMW'
            && (int) round((float) ($collection['amount'] ?? 0) * 100) === $payment->amount_ngwee;
    }

    private function markInvoicePaid(Invoice $invoice): void
    {
        if ($invoice->status === InvoiceStatus::Paid) {
            Log::warning('Invoice paid more than once; refund the duplicate payment', ['invoice' => $invoice->number]);

            return;
        }

        $invoice->update(['status' => InvoiceStatus::Paid, 'paid_at' => now()]);

        $subscription = $invoice->subscription;

        if (! $subscription) {
            return;
        }

        $periodStart = $subscription->current_period_ends_at?->isFuture() ? $subscription->current_period_ends_at : now();

        $subscription->update([
            'status' => SubscriptionStatus::Active,
            'current_period_ends_at' => $periodStart->copy()->addMonthNoOverflow(),
        ]);

        if (! $subscription->provisioned_at) {
            ProvisionHostingAccount::dispatch($subscription)->afterCommit();
        }
    }
}
