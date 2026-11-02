<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PartnerProgrammeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('marketing/Partners', [
            'rates' => config('partners.rates'),
            'leadOwnershipDays' => config('partners.lead_ownership_days'),
            'recurringMonths' => config('partners.recurring_commission_months'),
        ]);
    }
}
