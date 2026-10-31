<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\ConfirmPayment;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Team;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Open a payment attempt and hand the browser what the Lenco widget needs.
     */
    public function store(Request $request, Team $current_team, Invoice $invoice): JsonResponse
    {
        abort_unless($invoice->status === InvoiceStatus::Open, 409, 'This invoice is not open for payment.');

        $payment = $invoice->payments()->create([
            'reference' => 'VAR-'.Str::ulid(),
            'amount_ngwee' => $invoice->amount_ngwee,
            'status' => PaymentStatus::Pending,
        ]);

        return response()->json([
            'key' => config('services.lenco.public_key'),
            'reference' => $payment->reference,
            'email' => $request->user()->email,
            'amount' => $invoice->amountInKwacha(),
            'currency' => 'ZMW',
            'bearer' => config('services.lenco.fee_bearer'),
            'label' => $invoice->description,
        ], 201);
    }

    /**
     * Called after the widget reports success; the outcome is always re-checked with Lenco.
     */
    public function verify(Team $current_team, Invoice $invoice, Payment $payment, ConfirmPayment $confirmPayment): RedirectResponse
    {
        try {
            $payment = $confirmPayment->handle($payment);
        } catch (HttpClientException $e) {
            Log::warning('Could not verify Lenco payment', ['reference' => $payment->reference, 'error' => $e->getMessage()]);
        }

        return to_route('invoices.show', ['current_team' => $current_team->slug, 'invoice' => $invoice])->with('status', match ($payment->status) {
            PaymentStatus::Successful => 'payment-successful',
            PaymentStatus::Failed => 'payment-failed',
            PaymentStatus::Pending => 'payment-pending',
        });
    }
}
