<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    public function definition(): array
    {
        $startHour = fake()->numberBetween(6, 21);
        $endHour = $startHour + 1;

        return [
            'field_id' => Field::factory(),
            'start_time' => sprintf('%02d:00', $startHour),
            'end_time' => sprintf('%02d:00', $endHour),
            'is_active' => true,
        ];
    }
}
