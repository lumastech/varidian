<?php

namespace App\Http\Controllers\Billing;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Team;
use App\Services\Lenco\LencoClient;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function show(Team $current_team, Invoice $invoice, LencoClient $lenco): Response
    {
        return Inertia::render('billing/Invoice', [
            'invoice' => $invoice->toCustomerArray(),
            'canPay' => $invoice->status === InvoiceStatus::Open,
            'widgetUrl' => $lenco->widgetUrl(),
            'status' => session('status'),
        ]);
    }
}
