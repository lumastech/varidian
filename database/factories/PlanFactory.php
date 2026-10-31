<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::title(fake()->unique()->word());

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'audience' => fake()->sentence(4),
            'hestia_package' => $name,
            'price_ngwee' => fake()->numberBetween(50, 1000) * 100,
            'storage_gb' => 5,
            'bandwidth_gb' => 20,
            'websites' => 1,
            'databases' => 1,
            'cron_jobs' => 2,
            'backups' => 3,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    /**
     * Indicate that the plan is no longer offered.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
