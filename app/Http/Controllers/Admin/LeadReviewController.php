<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\ReviewLead;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadReviewRequest;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class LeadReviewController extends Controller
{
    public function __invoke(LeadReviewRequest $request, Lead $lead, ReviewLead $reviewLead): RedirectResponse
    {
        $reviewLead->handle(
            $lead,
            $request->validated('decision'),
            $request->validated('note'),
            $request->validated('team_id') ? Team::findOrFail($request->validated('team_id')) : null,
        );

        return back();
    }
}
