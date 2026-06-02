<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsSeeder extends Seeder
{
    public function run(): void
    {
        $ownerId = DB::table('users')->where('email', 'owner@example.com')->value('id');
        $footballId = DB::table('sports')->where('slug', 'football')->value('id');
        $badmintonId = DB::table('sports')->where('slug', 'badminton')->value('id');
        $tennisId = DB::table('sports')->where('slug', 'tennis')->value('id');

        $fields = [
            [
                'code' => 'FB-01',
                'sport_id' => $footballId,
                'name' => 'Football Field A',
                'description' => 'San bong da sanh co nhan tao',
                'address' => '12 Nguyen Van Linh, Da Nang',
                'price_per_hour' => 300000,
                'open_time' => '06:00:00',
                'close_time' => '22:00:00',
                'status' => 'active',
            ],
            [
                'code' => 'BD-01',
                'sport_id' => $badmintonId,
                'name' => 'Badminton Court A',
                'description' => 'San cau long trong nha 2 mat san',
                'address' => '45 Le Loi, Ho Chi Minh City',
                'price_per_hour' => 120000,
                'open_time' => '06:00:00',
                'close_time' => '23:00:00',
                'status' => 'active',
            ],
            [
                'code' => 'TN-01',
                'sport_id' => $tennisId,
                'name' => 'Tennis Court A',
                'description' => 'San tennis ngoai troi',
                'address' => '88 Tran Hung Dao, Ha Noi',
                'price_per_hour' => 250000,
                'open_time' => '05:30:00',
                'close_time' => '21:30:00',
                'status' => 'maintenance',
            ],
        ];

        foreach ($fields as $field) {
            DB::table('fields')->updateOrInsert(
                ['code' => $field['code']],
                array_merge($field, [
                    'owner_id' => $ownerId,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}