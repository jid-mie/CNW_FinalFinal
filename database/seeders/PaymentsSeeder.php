<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $bookingId = DB::table('bookings')
            ->orderByDesc('id')
            ->value('id');

        if (! $bookingId) {
            return;
        }

        DB::table('payments')->updateOrInsert(
            ['booking_id' => $bookingId],
            [
                'amount' => DB::table('bookings')->where('id', $bookingId)->value('total_price'),
                'method' => 'cash',
                'status' => 'paid',
                'transaction_code' => 'TXN-'.Str::upper(Str::random(8)),
                'paid_at' => now(),
                'note' => 'Payment demo for Postman testing',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}