<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsSeeder extends Seeder
{
    public function run(): void
    {
        $customerId = DB::table('users')->where('email', 'customer@example.com')->value('id');
        $fieldId = DB::table('fields')->where('code', 'FB-01')->value('id');
        $timeSlotId = DB::table('time_slots')
            ->where('field_id', $fieldId)
            ->where('start_time', '18:00:00')
            ->where('end_time', '19:00:00')
            ->value('id');

        if (! $customerId || ! $fieldId || ! $timeSlotId) {
            return;
        }

        DB::table('bookings')->updateOrInsert(
            [
                'customer_id' => $customerId,
                'field_id' => $fieldId,
                'time_slot_id' => $timeSlotId,
                'booking_date' => now()->addDay()->toDateString(),
            ],
            [
                'total_price' => 300000,
                'status' => 'confirmed',
                'note' => 'Booking demo for Postman testing',
                'confirmed_at' => now(),
                'cancelled_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
