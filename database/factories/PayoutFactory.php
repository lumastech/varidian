<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\Payout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payout>
 */
class PayoutFactory extends Factory
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
            'amount_ngwee' => 75000,
            'method' => 'mobile-money',
            'reference' => fake()->bothify('MP######.####.??????'),
            'paid_at' => now(),
        ];
    }
}
