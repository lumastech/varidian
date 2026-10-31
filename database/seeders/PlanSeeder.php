<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the shared hosting plans. Each `hestia_package` must match a package on the Hestia server.
     */
    public function run(): void
    {
        $plans = [
            ['name' => 'Community', 'audience' => 'For personal sites, clubs and churches', 'price_ngwee' => 9900, 'storage_gb' => 1, 'bandwidth_gb' => 10, 'websites' => 1, 'databases' => 1, 'cron_jobs' => 2, 'backups' => 3],
            ['name' => 'Starter', 'audience' => 'For a small business website', 'price_ngwee' => 19900, 'storage_gb' => 5, 'bandwidth_gb' => 20, 'websites' => 1, 'databases' => 1, 'cron_jobs' => 2, 'backups' => 3],
            ['name' => 'Business', 'audience' => 'For growing SMEs with several sites', 'price_ngwee' => 49900, 'storage_gb' => 20, 'bandwidth_gb' => 75, 'websites' => 5, 'databases' => 10, 'cron_jobs' => 10, 'backups' => 7, 'is_featured' => true],
            ['name' => 'Developer', 'audience' => 'For developers and agencies hosting clients', 'price_ngwee' => 89900, 'storage_gb' => 40, 'bandwidth_gb' => 150, 'websites' => 10, 'databases' => 20, 'cron_jobs' => 25, 'backups' => 7],
        ];

        foreach ($plans as $index => $plan) {
            Plan::updateOrCreate(
                ['slug' => strtolower($plan['name'])],
                [...$plan, 'hestia_package' => $plan['name'], 'sort_order' => $index],
            );
        }
    }
}
