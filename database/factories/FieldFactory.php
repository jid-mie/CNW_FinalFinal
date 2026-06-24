<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    private static array $samples = [];

    public function definition(): array
    {
        if (empty(static::$samples)) {
            static::$samples = [
                ['Sân 1', 'Sân bóng đá mini cỏ nhân tạo, đạt chuẩn thi đấu'],
                ['Sân 2', 'Sân bóng đá 5 người, đèn cao áp'],
                ['Sân VIP', 'Sân bóng đá tiêu chuẩn, có khán đài'],
                ['Sân A', 'Sân cầu lông trong nhà, sàn gỗ cao cấp'],
                ['Sân B', 'Sân cầu lông, có điều hòa'],
                ['Sân chính', 'Sân bóng rổ ngoài trời, tiêu chuẩn FIBA'],
                ['Sân phụ', 'Sân bóng rổ trong nhà, có mái che'],
                ['Sân 1', 'Sân pickleball ngoài trời, mặt sân cứng'],
            ];
        }

        $pair = fake()->randomElement(static::$samples);

        return [
            'owner_id' => User::factory(),
            'sport_id' => Sport::factory(),
            'name' => $pair[0],
            'code' => strtoupper(fake()->bothify('??-####')),
            'description' => $pair[1],
            'address' => fake()->address(),
            'price_per_hour' => fake()->randomElement([100000, 150000, 200000, 250000, 300000, 400000, 500000]),
            'open_time' => '06:00',
            'close_time' => '22:00',
            'image' => null,
            'status' => fake()->randomElement(['active', 'active', 'active', 'maintenance', 'inactive']),
        ];
    }
}
