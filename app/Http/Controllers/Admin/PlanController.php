<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanRequest;
use App\Models\Plan;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    /**
     * Hestia package fields that should agree with each plan: plan attribute => [package key, MB per unit].
     *
     * @var array<string, array{0: string, 1: int}>
     */
    private const array PackageLimits = [
        'storage_gb' => ['DISK_QUOTA', 1024],
        'bandwidth_gb' => ['BANDWIDTH', 1024],
        'websites' => ['WEB_DOMAINS', 1],
        'databases' => ['DATABASES', 1],
        'cron_jobs' => ['CRON_JOBS', 1],
        'backups' => ['BACKUPS', 1],
    ];

    public function index(HestiaClient $hestia): Response
    {
        return Inertia::render('admin/plans/Index', [
            'plans' => Plan::query()
                ->withCount(['subscriptions as active_subscriptions_count' => fn ($query) => $query->whereNot('status', SubscriptionStatus::Cancelled)])
                ->orderBy('sort_order')
                ->orderBy('price_ngwee')
                ->get()
                ->map(fn (Plan $plan) => [
                    ...$plan->toMarketingArray(),
                    'id' => $plan->id,
                    'hestiaPackage' => $plan->hestia_package,
                    'isActive' => $plan->is_active,
                    'customers' => $plan->active_subscriptions_count,
                ]),
            'packageMismatches' => Inertia::defer(fn () => $this->packageMismatches($hestia)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/plans/Edit', ['plan' => null]);
    }

    public function store(PlanRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->planAttributes());

        Log::info('Hosting plan created', ['plan' => $plan->slug, 'by' => $request->user()->id]);

        return to_route('admin.plans.index');
    }

    public function edit(Plan $plan): Response
    {
        return Inertia::render('admin/plans/Edit', [
            'plan' => [
                'slug' => $plan->slug,
                'name' => $plan->name,
                'audience' => $plan->audience,
                'hestia_package' => $plan->hestia_package,
                'price' => number_format($plan->price_ngwee / 100, 2, '.', ''),
                'storage_gb' => $plan->storage_gb,
                'bandwidth_gb' => $plan->bandwidth_gb,
                'websites' => $plan->websites,
                'databases' => $plan->databases,
                'cron_jobs' => $plan->cron_jobs,
                'backups' => $plan->backups,
                'is_featured' => $plan->is_featured,
                'is_active' => $plan->is_active,
                'sort_order' => $plan->sort_order,
            ],
        ]);
    }

    /**
     * New prices apply to sign-ups straight away and to existing customers from their next
     * renewal invoice; invoices already issued keep the price they were issued at.
     */
    public function update(PlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->fill($request->planAttributes());

        if ($plan->isDirty('price_ngwee')) {
            Log::info('Hosting plan price changed', [
                'plan' => $plan->slug,
                'from' => $plan->getOriginal('price_ngwee'),
                'to' => $plan->price_ngwee,
                'by' => $request->user()->id,
            ]);
        }

        $plan->save();

        return to_route('admin.plans.index');
    }

    /**
     * Compare each plan with its Hestia package so staff notice when the two disagree.
     *
     * @return array<string, list<string>>|null
     */
    private function packageMismatches(HestiaClient $hestia): ?array
    {
        try {
            $packages = $hestia->listPackages();
        } catch (HestiaException|HttpClientException) {
            return null;
        }

        return Plan::all()->mapWithKeys(function (Plan $plan) use ($packages) {
            $package = $packages[$plan->hestia_package] ?? null;

            if ($package === null) {
                return [$plan->slug => ['Package not found on the server']];
            }

            $problems = [];

            foreach (self::PackageLimits as $attribute => [$key, $unit]) {
                $limit = $package[$key] ?? null;

                if (is_numeric($limit) && (int) $limit !== $plan->{$attribute} * $unit) {
                    $problems[] = str($attribute)->replace('_', ' ')->ucfirst().": plan {$plan->{$attribute}}, server ".((int) $limit / $unit);
                }
            }

            return [$plan->slug => $problems];
        })->filter()->all();
    }
}
