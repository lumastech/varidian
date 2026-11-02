<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Enums\LeadStatus;
use App\Enums\PartnerStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\Subscription;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'subscriptions' => Subscription::query()
                ->with(['team', 'plan'])
                ->latest('id')
                ->limit(100)
                ->get()
                ->map(fn (Subscription $subscription) => [
                    'id' => $subscription->id,
                    'domain' => $subscription->domain,
                    'team' => $subscription->team->name,
                    'plan' => $subscription->plan->name,
                    'username' => $subscription->hestia_username,
                    'status' => $subscription->status->value,
                    'statusLabel' => $subscription->status->label(),
                    'renewsAt' => $subscription->current_period_ends_at?->toDateString(),
                    'isCancelling' => $subscription->isCancelling(),
                    'isProvisioned' => $subscription->provisioned_at !== null,
                    'hasSsl' => $subscription->ssl_issued_at !== null,
                ]),
            'openInvoices' => Invoice::query()
                ->with('team')
                ->where('status', InvoiceStatus::Open)
                ->oldest('due_at')
                ->get()
                ->map(fn (Invoice $invoice) => [...$invoice->toCustomerArray(), 'team' => $invoice->team->name]),
            'inquiries' => ContactInquiry::query()
                ->latest('id')
                ->limit(25)
                ->get(['id', 'name', 'organisation', 'phone', 'email', 'product_interest', 'message', 'mailed_at', 'created_at']),
            'partnerItemsToReview' => Partner::query()->where('status', PartnerStatus::Pending)->count()
                + Lead::query()->where('status', LeadStatus::Pending)->count(),
        ]);
    }
}
