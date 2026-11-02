<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Enums\PartnerStatus;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\Payout;
use App\Models\Team;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/partners/Index', [
            'partners' => Partner::query()
                ->with('user')
                ->withCount(['leads', 'leads as won_leads_count' => fn ($query) => $query->where('status', LeadStatus::Won)])
                ->withSum(['commissions as due_ngwee' => fn ($query) => $query->dueForPayout()], 'amount_ngwee')
                ->latest('id')
                ->get()
                ->map(fn (Partner $partner) => [
                    ...$this->partnerSummary($partner),
                    'leads' => $partner->leads_count,
                    'wonLeads' => $partner->won_leads_count,
                    'due' => Money::format((int) $partner->due_ngwee),
                ]),
            'leadsToReview' => Lead::query()
                ->with('partner')
                ->where('status', LeadStatus::Pending)
                ->oldest('id')
                ->get()
                ->map(fn (Lead $lead) => [
                    ...$lead->toPortalArray(),
                    'partner' => $lead->partner->full_name,
                    'partnerId' => $lead->partner_id,
                    'conflicts' => $this->conflicts($lead),
                ]),
        ]);
    }

    public function show(Partner $partner): Response
    {
        $partner->load('user');

        return Inertia::render('admin/partners/Show', [
            'partner' => [
                ...$this->partnerSummary($partner),
                'nrcNumber' => $partner->nrc_number,
                'payoutMethod' => $partner->payout_method,
                'payoutDetails' => $partner->payout_details,
                'agreementAcceptedAt' => $partner->agreement_accepted_at->toDateString(),
                'due' => Money::format($partner->amountDueNgwee()),
            ],
            'leads' => $partner->leads()->with('team')->latest('id')->get()
                ->map(fn (Lead $lead) => [...$lead->toPortalArray(), 'team' => $lead->team?->name]),
            'commissions' => $partner->commissions()->with('lead')->latest('earned_at')->get()
                ->map(fn (Commission $commission) => $commission->toStatementArray()),
            'payouts' => $partner->payouts()->latest('paid_at')->get()->map(fn (Payout $payout) => $payout->toSummaryArray()),
            'teams' => Team::query()->orderBy('name')->get(['id', 'name']),
            'rates' => config('partners.rates'),
        ]);
    }

    /**
     * Approve an application, or end a partner's agreement.
     */
    public function updateStatus(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', 'in:active,terminated']]);

        $partner->update($validated['status'] === PartnerStatus::Active->value
            ? ['status' => PartnerStatus::Active, 'approved_at' => $partner->approved_at ?? now(), 'terminated_at' => null]
            : ['status' => PartnerStatus::Terminated, 'terminated_at' => now()]);

        Log::info('Sales partner status changed', ['partner' => $partner->id, 'status' => $partner->status->value, 'by' => $request->user()->id]);

        return back();
    }

    /**
     * @return array{id: int, fullName: string, email: string, phone: string, status: string, statusLabel: string, appliedAt: string}
     */
    private function partnerSummary(Partner $partner): array
    {
        return [
            'id' => $partner->id,
            'fullName' => $partner->full_name,
            'email' => $partner->user->email,
            'phone' => $partner->phone,
            'status' => $partner->status->value,
            'statusLabel' => $partner->status->label(),
            'appliedAt' => $partner->created_at->toDateString(),
        ];
    }

    /**
     * Find other partners' leads and existing clients with the same name, so staff can flag them.
     *
     * @return list<string>
     */
    private function conflicts(Lead $lead): array
    {
        $name = mb_strtolower(trim($lead->organisation));

        $otherLeads = Lead::query()
            ->with('partner')
            ->held()
            ->whereKeyNot($lead->id)
            ->whereRaw('lower(organisation) = ?', [$name])
            ->get()
            ->map(fn (Lead $other) => "Registered by {$other->partner->full_name} on {$other->created_at->toDateString()}");

        $clients = Team::query()
            ->whereRaw('lower(name) = ?', [$name])
            ->has('invoices')
            ->pluck('name')
            ->map(fn (string $team) => "Existing client account: {$team}");

        return [...$otherLeads, ...$clients];
    }
}
