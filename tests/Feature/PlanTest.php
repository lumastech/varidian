<?php

use App\Models\Plan;
use Database\Seeders\PlanSeeder;
use Inertia\Testing\AssertableInertia;

test('the seeder can be re-run without duplicating plans', function () {
    $this->seed(PlanSeeder::class);
    $this->seed(PlanSeeder::class);

    expect(Plan::count())->toBe(4);
});

test('marketing arrays describe the plan limits', function () {
    $plan = Plan::factory()->make([
        'price_ngwee' => 49900, 'storage_gb' => 20, 'bandwidth_gb' => 75,
        'websites' => 5, 'databases' => 1, 'cron_jobs' => 10, 'backups' => 7,
    ]);

    expect($plan->toMarketingArray())
        ->price->toBe('499')
        ->spec->toBe('5 websites · 20GB storage')
        ->features->toBe(['20GB storage', '75GB bandwidth', '5 websites', '1 database', '10 cron jobs', '7 backups kept']);
});

test('hosting and home pages receive only active plans', function (string $url, string $component) {
    $this->seed(PlanSeeder::class);
    Plan::factory()->inactive()->create(['name' => 'Legacy']);

    $this->get($url)
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component($component)
            ->has('plans', 4)
            ->where('plans.0.name', 'Community')
            ->where('plans.2.featured', true));
})->with([
    ['/', 'Welcome'],
    ['/hosting', 'marketing/Hosting'],
]);
