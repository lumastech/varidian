<?php

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'plan_id' => Plan::factory(),
            'domain' => fake()->unique()->domainWord().'.co.zm',
            'hestia_username' => 'u'.fake()->unique()->numerify('########'),
            'status' => SubscriptionStatus::Pending,
        ];
    }

    /**
     * Indicate that the subscription is paid up and provisioned.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SubscriptionStatus::Active,
            'current_period_ends_at' => now()->addMonth(),
            'provisioned_at' => now(),
        ]);
    }
}
