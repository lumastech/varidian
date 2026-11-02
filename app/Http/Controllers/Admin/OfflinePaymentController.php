<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Billing\MarkInvoicePaid;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfflinePaymentController extends Controller
{
    /**
     * Record a bank transfer or direct mobile money payment received outside Lenco.
     */
    public function __invoke(Request $request, Invoice $invoice, MarkInvoicePaid $markInvoicePaid): RedirectResponse
    {
        $validated = $request->validate([
            'method' => ['required', 'in:bank-transfer,mobile-money,cash'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        abort_unless($invoice->status === InvoiceStatus::Open, 409, 'This invoice is not open.');

        DB::transaction(function () use ($invoice, $validated, $request, $markInvoicePaid) {
            $invoice->payments()->create([
                'reference' => 'OFFLINE-'.Str::ulid(),
                'amount_ngwee' => $invoice->amount_ngwee,
                'status' => PaymentStatus::Successful,
                'channel' => $validated['method'],
                'provider_response' => ['recorded_by' => $request->user()->id, 'note' => $validated['note'] ?? null],
                'paid_at' => now(),
            ]);

            $markInvoicePaid->handle($invoice);
        });

        return back();
    }
}
