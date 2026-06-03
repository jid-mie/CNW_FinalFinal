<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['paid', 'unpaid', 'paid', 'paid', 'refunded']);

        return [
            'booking_id' => Booking::factory(),
            'amount' => fake()->randomElement([100000, 150000, 200000, 300000, 400000]),
            'method' => fake()->randomElement(['cash', 'bank_transfer', 'momo', 'vnpay']),
            'status' => $status,
            'transaction_code' => $status !== 'unpaid' ? strtoupper(fake()->bothify('TXN-########')) : null,
            'paid_at' => $status === 'paid' ? now()->subHours(rand(1, 72)) : null,
            'note' => fake()->optional(0.2)->sentence(),
        ];
    }
}
