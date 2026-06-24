<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    private static array $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

    public function definition(): array
    {
        $status = fake()->randomElement(static::$statuses);

        return [
            'customer_id' => User::factory(),
            'field_id' => Field::factory(),
            'time_slot_id' => TimeSlot::factory(),
            'booking_date' => fake()->dateTimeBetween('-7 days', '+30 days')->format('Y-m-d'),
            'total_price' => fake()->randomElement([100000, 150000, 200000, 300000]),
            'status' => $status,
            'note' => fake()->optional(0.3)->sentence(),
            'confirmed_at' => in_array($status, ['confirmed', 'completed']) ? now()->subHours(rand(1, 48)) : null,
            'cancelled_at' => $status === 'cancelled' ? now()->subHours(rand(1, 24)) : null,
        ];
    }
}
