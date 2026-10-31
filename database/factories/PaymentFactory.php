<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'reference' => 'VAR-'.Str::upper(Str::random(16)),
            'amount_ngwee' => fn (array $attributes) => Invoice::find($attributes['invoice_id'])->amount_ngwee,
            'status' => PaymentStatus::Pending,
        ];
    }
}
