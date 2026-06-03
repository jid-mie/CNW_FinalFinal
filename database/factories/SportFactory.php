<?php

namespace Database\Factories;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Sport>
 */
class SportFactory extends Factory
{
    private static array $sports = [
        ['name' => 'Bóng đá', 'slug' => 'bong-da'],
        ['name' => 'Bóng chuyền', 'slug' => 'bong-chuyen'],
        ['name' => 'Bóng rổ', 'slug' => 'bong-ro'],
        ['name' => 'Cầu lông', 'slug' => 'cau-long'],
        ['name' => 'Tennis', 'slug' => 'tennis'],
        ['name' => 'Bóng bàn', 'slug' => 'bong-ban'],
        ['name' => 'Pickleball', 'slug' => 'pickleball'],
        ['name' => 'Đá cầu', 'slug' => 'da-cau'],
    ];

    public function definition(): array
    {
        $index = $this->faker->unique()->numberBetween(0, count(static::$sports) - 1);
        $sport = static::$sports[$index];

        return [
            'name' => $sport['name'],
            'slug' => $sport['slug'],
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
