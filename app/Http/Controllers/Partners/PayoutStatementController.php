<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Payout;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PayoutStatementController extends Controller
{
    public function __invoke(Request $request, Payout $payout): Response
    {
        abort_unless(
            $payout->partner_id === $request->user()->partner?->id || $request->user()->can('access-admin'),
            404,
        );

        return Inertia::render('partners/Statement', [
            'payout' => $payout->toSummaryArray(),
            'partner' => ['fullName' => $payout->partner->full_name, 'payoutDetails' => $payout->partner->payout_details],
            'commissions' => $payout->commissions()->with('lead')->oldest('earned_at')->get()
                ->map(fn (Commission $commission) => $commission->toStatementArray()),
        ]);
    }
}
