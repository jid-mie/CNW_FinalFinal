<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SportsSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            ['name' => 'Football', 'description' => 'Sân bóng đá 5, 7, 11 người'],
            ['name' => 'Badminton', 'description' => 'Sân cầu lông trong nhà'],
            ['name' => 'Tennis', 'description' => 'Sân tennis tiêu chuẩn'],
        ];

        foreach ($sports as $sport) {
            DB::table('sports')->updateOrInsert(
                ['slug' => Str::slug($sport['name'])],
                [
                    'name' => $sport['name'],
                    'description' => $sport['description'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}