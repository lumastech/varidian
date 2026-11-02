<?php

namespace App\Http\Controllers\Partners;

use App\Enums\PartnerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\PartnerApplicationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PartnerApplicationController extends Controller
{
    public function create(Request $request): Response|RedirectResponse
    {
        if ($request->user()->partner) {
            return to_route('partner.dashboard');
        }

        return Inertia::render('partners/Apply', [
            'rates' => config('partners.rates'),
            'leadOwnershipDays' => config('partners.lead_ownership_days'),
            'recurringMonths' => config('partners.recurring_commission_months'),
        ]);
    }

    /**
     * Sign the agreement and apply; staff approve partners before they can register leads.
     */
    public function store(PartnerApplicationRequest $request): RedirectResponse
    {
        abort_if($request->user()->partner()->exists(), 409, 'You have already applied.');

        $partner = $request->user()->partner()->create([
            ...$request->safe()->except('accept_agreement'),
            'status' => PartnerStatus::Pending,
            'agreement_accepted_at' => now(),
        ]);

        Log::info('Sales partner application received', ['partner' => $partner->id]);

        return to_route('partner.dashboard');
    }
}
