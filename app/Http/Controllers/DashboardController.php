<?php

namespace App\Http\Controllers;

use App\Actions\Billing\ChangePlan;
use App\Models\Subscription;
use App\Models\Team;
use App\Services\Hestia\HostingUsage;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Team $current_team, HostingUsage $hostingUsage): Response
    {
        return Inertia::render('Dashboard', [
            'subscriptions' => $current_team->subscriptions()
                ->with(['plan', 'scheduledPlan', 'invoices' => fn ($query) => $query->latest('id')->limit(1)])
                ->latest('id')
                ->get()
                ->map(fn (Subscription $subscription) => [
                    'id' => $subscription->id,
                    'domain' => $subscription->domain,
                    'plan' => $subscription->plan->name,
                    'username' => $subscription->hestia_username,
                    'status' => $subscription->status->value,
                    'statusLabel' => $subscription->status->label(),
                    'renewsAt' => $subscription->current_period_ends_at?->toDateString(),
                    'isProvisioned' => $subscription->provisioned_at !== null,
                    'isCancelling' => $subscription->isCancelling(),
                    'scheduledPlan' => $subscription->scheduledPlan?->name,
                    'canChangePlan' => app(ChangePlan::class)->canChange($subscription),
                    'latestInvoice' => $subscription->invoices->first()?->toCustomerArray(),
                ]),
            'invoices' => $current_team->invoices()->latest('id')->limit(10)->get()->map->toCustomerArray(),
            'panelUrl' => config('services.hestia.panel_url'),
            'usage' => Inertia::defer(fn () => $current_team->subscriptions()
                ->whereNotNull('provisioned_at')
                ->get()
                ->mapWithKeys(fn (Subscription $subscription) => [$subscription->id => $hostingUsage->for($subscription)])),
        ]);
    }
}
