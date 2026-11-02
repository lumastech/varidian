<?php

namespace Database\Factories;

use App\Enums\PartnerStatus;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'nrc_number' => fake()->numerify('######/##/1'),
            'phone' => fake()->numerify('+26097#######'),
            'payout_method' => 'mobile-money',
            'payout_details' => 'Airtel Money '.fake()->numerify('097#######'),
            'status' => PartnerStatus::Active,
            'agreement_accepted_at' => now()->subMonths(2),
            'approved_at' => now()->subMonths(2),
        ];
    }

    /**
     * Indicate that the partner has applied but not been approved.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerStatus::Pending,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the partner's agreement has ended.
     */
    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerStatus::Terminated,
            'terminated_at' => now(),
        ]);
    }
}
