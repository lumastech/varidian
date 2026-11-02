<?php

namespace App\Http\Controllers\Billing;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Contracts\View\View;

class InvoicePrintController extends Controller
{
    /**
     * A print-ready invoice, or receipt once paid; customers save it as PDF from the browser.
     */
    public function __invoke(Team $current_team, Invoice $invoice): View
    {
        return view('invoices.print', [
            'invoice' => $invoice,
            'team' => $current_team,
            'payment' => $invoice->payments()->where('status', PaymentStatus::Successful)->latest('paid_at')->first(),
            'contact' => config('contact'),
        ]);
    }
}
