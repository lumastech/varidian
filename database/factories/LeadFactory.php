<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
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
            'organisation' => fake()->unique()->company(),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->numerify('+26097#######'),
            'contact_email' => fake()->safeEmail(),
            'interest' => 'Varidian Hosting',
            'status' => LeadStatus::Pending,
        ];
    }

    /**
     * Indicate that Varidian has confirmed the partner owns the lead.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeadStatus::Confirmed,
            'confirmed_at' => now(),
            'expires_at' => now()->addDays(config('partners.lead_ownership_days')),
        ]);
    }

    /**
     * Indicate that the lead became a paying client.
     */
    public function won(?Team $team = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeadStatus::Won,
            'confirmed_at' => now()->subMonthNoOverflow(),
            'expires_at' => now()->addMonths(2),
            'team_id' => $team ?? Team::factory(),
            'won_at' => now(),
        ]);
    }
}
