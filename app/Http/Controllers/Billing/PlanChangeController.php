<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\ChangePlan;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanChangeController extends Controller
{
    public function edit(Team $current_team, Subscription $subscription, ChangePlan $changePlan): Response
    {
        $subscription->load(['plan', 'scheduledPlan']);

        abort_unless($changePlan->canChange($subscription), 409, 'Plan changes are only possible on active, paid-up hosting.');

        return Inertia::render('billing/ChangePlan', [
            'subscription' => [
                'id' => $subscription->id,
                'domain' => $subscription->domain,
                'plan' => $subscription->plan->slug,
                'scheduledPlan' => $subscription->scheduledPlan?->slug,
                'renewsAt' => $subscription->current_period_ends_at->toDateString(),
            ],
            'plans' => Plan::query()->forSale()->get()->map(fn (Plan $plan) => [
                ...$plan->toMarketingArray(),
                'isUpgrade' => $plan->price_ngwee > $subscription->plan->price_ngwee,
                'upgradeCost' => $plan->price_ngwee > $subscription->plan->price_ngwee
                    ? number_format($changePlan->upgradeCost($subscription, $plan) / 100, 2)
                    : null,
            ]),
        ]);
    }

    public function update(Request $request, Team $current_team, Subscription $subscription, ChangePlan $changePlan): RedirectResponse
    {
        $request->validate(['plan' => ['required', 'string', 'exists:plans,slug']]);

        $invoice = $changePlan->handle($subscription->load('plan'), Plan::where('slug', $request->input('plan'))->sole());

        return $invoice
            ? to_route('invoices.show', ['current_team' => $current_team->slug, 'invoice' => $invoice])
            : to_route('dashboard', ['current_team' => $current_team->slug]);
    }
}
