<?php

namespace Database\Factories;

use App\Enums\CommissionType;
use App\Models\Commission;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commission>
 */
class CommissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_id' => Partner::factory(),
            'type' => CommissionType::Licence,
            'description' => 'BizManager licence',
            'base_ngwee' => 500000,
            'rate_percent' => 15,
            'amount_ngwee' => 75000,
            'earned_at' => now()->subMonthNoOverflow(),
        ];
    }
}
