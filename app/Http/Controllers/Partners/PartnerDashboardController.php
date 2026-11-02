<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Lead;
use App\Models\Payout;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PartnerDashboardController extends Controller
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        $partner = $request->user()->partner;

        if (! $partner) {
            return to_route('partner.apply');
        }

        return Inertia::render('partners/Dashboard', [
            'partner' => [
                'fullName' => $partner->full_name,
                'nrcNumber' => $partner->nrc_number,
                'status' => $partner->status->value,
                'statusLabel' => $partner->status->label(),
                'payoutMethod' => $partner->payout_method,
                'payoutDetails' => $partner->payout_details,
                'agreementAcceptedAt' => $partner->agreement_accepted_at->toDateString(),
            ],
            'summary' => [
                'due' => Money::format($partner->amountDueNgwee()),
                'thisMonth' => Money::format((int) $partner->commissions()->whereNull('payout_id')->where('earned_at', '>=', now()->startOfMonth())->sum('amount_ngwee')),
                'paid' => Money::format((int) $partner->payouts()->sum('amount_ngwee')),
            ],
            'leads' => $partner->leads()->latest('id')->get()->map(fn (Lead $lead) => $lead->toPortalArray()),
            'commissions' => $partner->commissions()->with('lead')->latest('earned_at')->limit(50)->get()
                ->map(fn (Commission $commission) => $commission->toStatementArray()),
            'payouts' => $partner->payouts()->latest('paid_at')->get()->map(fn (Payout $payout) => $payout->toSummaryArray()),
            'leadOwnershipDays' => config('partners.lead_ownership_days'),
        ]);
    }
}
