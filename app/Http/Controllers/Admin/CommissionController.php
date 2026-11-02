<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommissionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommissionRequest;
use App\Models\Commission;
use App\Models\Partner;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    /**
     * Record commission on a payment Varidian received outside this system, or an adjustment such as a refund.
     */
    public function store(CommissionRequest $request, Partner $partner): RedirectResponse
    {
        $type = CommissionType::from($request->validated('type'));

        $commission = $partner->commissions()->create([
            'lead_id' => $request->validated('lead_id'),
            'type' => $type,
            'description' => $request->validated('description'),
            'earned_at' => $request->date('earned_on'),
            ...Commission::calculate($type, Money::toNgwee($request->validated('amount'))),
        ]);

        Log::info('Partner commission recorded', ['commission' => $commission->id, 'by' => $request->user()->id]);

        return back();
    }
}
