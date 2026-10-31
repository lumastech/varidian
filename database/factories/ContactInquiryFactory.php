<?php

namespace Database\Factories;

use App\Models\ContactInquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactInquiry>
 */
class ContactInquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'organisation' => fake()->company(),
            'phone' => '+26097'.fake()->numerify('#######'),
            'email' => fake()->safeEmail(),
            'product_interest' => 'Web hosting',
            'message' => fake()->paragraph(),
        ];
    }
}
