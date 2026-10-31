<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\StartSubscription;
use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StartSubscriptionRequest;
use App\Models\Plan;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Send a visitor from the marketing site into checkout for their current team.
     */
    public function order(Request $request, Plan $plan): RedirectResponse
    {
        $team = $request->user()->currentTeam ?? $request->user()->personalTeam();

        return to_route('checkout.create', ['current_team' => $team->slug, 'plan' => $plan->slug]);
    }

    public function create(Team $current_team, Plan $plan): Response
    {
        abort_unless($plan->is_active, 404);

        return Inertia::render('billing/Checkout', [
            'plan' => $plan->toMarketingArray(),
        ]);
    }

    public function store(StartSubscriptionRequest $request, Team $current_team, Plan $plan, StartSubscription $startSubscription): RedirectResponse
    {
        abort_unless($plan->is_active, 404);

        $invoice = $startSubscription->handle($current_team, $plan, $request->validated('domain'));

        return to_route('invoices.show', ['current_team' => $current_team->slug, 'invoice' => $invoice]);
    }
}
