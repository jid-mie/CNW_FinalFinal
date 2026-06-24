<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSlotsSeeder extends Seeder
{
    public function run(): void
    {
        $fieldIds = DB::table('fields')->pluck('id', 'code');

        $timeSlots = [
            'FB-01' => [
                ['06:00:00', '07:00:00'],
                ['07:00:00', '08:00:00'],
                ['18:00:00', '19:00:00'],
            ],
            'BD-01' => [
                ['06:00:00', '07:30:00'],
                ['07:30:00', '09:00:00'],
            ],
            'TN-01' => [
                ['17:00:00', '18:30:00'],
                ['18:30:00', '20:00:00'],
            ],
        ];

        foreach ($timeSlots as $code => $slots) {
            $fieldId = $fieldIds[$code] ?? null;

            if (! $fieldId) {
                continue;
            }

            foreach ($slots as [$startTime, $endTime]) {
                DB::table('time_slots')->updateOrInsert(
                    [
                        'field_id' => $fieldId,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ],
                    [
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
