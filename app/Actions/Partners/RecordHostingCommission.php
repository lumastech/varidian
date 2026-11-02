<?php

namespace App\Actions\Partners;

use App\Enums\CommissionType;
use App\Enums\InvoiceStatus;
use App\Enums\LeadStatus;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\Lead;
use Carbon\CarbonImmutable;

class RecordHostingCommission
{
    /**
     * Credit the partner who signed this client with commission on a paid hosting invoice.
     * Safe to call more than once for the same invoice.
     */
    public function handle(Invoice $invoice): void
    {
        if ($invoice->status !== InvoiceStatus::Paid) {
            return;
        }

        $lead = Lead::query()
            ->with('partner')
            ->whereBelongsTo($invoice->team)
            ->where('status', LeadStatus::Won)
            ->where('created_at', '<=', $invoice->paid_at)
            ->latest('won_at')
            ->first();

        if (! $lead || ! $this->withinCommissionPeriod($lead, $invoice)) {
            return;
        }

        // A partner keeps earning on clients signed before their agreement ended.
        if ($lead->partner->terminated_at && $lead->partner->terminated_at->lt($lead->won_at)) {
            return;
        }

        Commission::query()->firstOrCreate(['invoice_id' => $invoice->id], [
            'partner_id' => $lead->partner_id,
            'lead_id' => $lead->id,
            'type' => CommissionType::Hosting,
            'description' => "{$invoice->number}: {$invoice->description}",
            'earned_at' => $invoice->paid_at,
            ...Commission::calculate(CommissionType::Hosting, $invoice->amount_ngwee),
        ]);
    }

    /**
     * Hosting earns commission for the client's first months of paid service after the lead was registered.
     */
    private function withinCommissionPeriod(Lead $lead, Invoice $invoice): bool
    {
        $firstPaidAt = $invoice->team->invoices()
            ->where('status', InvoiceStatus::Paid)
            ->where('paid_at', '>=', $lead->created_at)
            ->min('paid_at');

        return $firstPaidAt !== null
            && $invoice->paid_at->lt(CarbonImmutable::parse($firstPaidAt)->addMonthsNoOverflow(config('partners.recurring_commission_months')));
    }
}
