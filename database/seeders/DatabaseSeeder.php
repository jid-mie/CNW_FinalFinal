<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Sport;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── 1. Roles ──
        $roles = [
            'admin'    => Role::create(['name' => 'admin',    'display_name' => 'Admin']),
            'owner'    => Role::create(['name' => 'owner',    'display_name' => 'Chủ sân']),
            'customer' => Role::create(['name' => 'customer', 'display_name' => 'Khách hàng']),
        ];

        // ── 2. Admin ──
        $admin = User::create([
            'role_id' => $roles['admin']->id,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '0900000000',
            'address' => 'Hà Nội',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── 3. Owners ──
        $ownerUsers = [];
        $ownerData = [
            ['Nguyễn Văn A', 'owner1@example.com', '0912345678', 'Cầu Giấy, Hà Nội'],
            ['Trần Thị B',   'owner2@example.com', '0923456789', 'Đống Đa, Hà Nội'],
            ['Lê Văn C',     'owner3@example.com', '0934567890', 'Thanh Xuân, Hà Nội'],
        ];
        foreach ($ownerData as [$name, $email, $phone, $address]) {
            $ownerUsers[] = User::create([
                'role_id' => $roles['owner']->id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // ── 4. Sports ──
        $sports = [];
        $sportData = [
            ['Bóng đá',      'bong-da',      'Sân bóng đá mini, sân 11 người'],
            ['Bóng chuyền',  'bong-chuyen',  'Sân bóng chuyền trong nhà & ngoài trời'],
            ['Bóng rổ',      'bong-ro',      'Sân bóng rổ tiêu chuẩn'],
            ['Cầu lông',     'cau-long',     'Sân cầu lông trong nhà'],
            ['Tennis',       'tennis',       'Sân tennis sân cứng & sân đất nện'],
            ['Bóng bàn',     'bong-ban',     'Bàn bóng bàn trong nhà'],
            ['Pickleball',   'pickleball',   'Sân pickleball'],
            ['Đá cầu',       'da-cau',       'Sân đá cầu ngoài trời'],
        ];
        foreach ($sportData as [$name, $slug, $desc]) {
            $sports[] = Sport::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $desc,
                'is_active' => true,
            ]);
        }

        // ── 5. Fields (3 fields per owner) ──
        $fieldNames = [
            ['Sân chính', 'Sân phụ', 'Sân VIP'],
            ['Sân A', 'Sân B', 'Sân tập'],
            ['Sân 1', 'Sân 2', 'Sân 3'],
        ];
        $addresses = [
            ['Số 1, Nguyễn Trãi, Cầu Giấy', 'Số 10, Trần Duy Hưng, Cầu Giấy', 'Số 20, Xuân Thủy, Cầu Giấy'],
            ['Số 5, Tôn Đức Thắng, Đống Đa', 'Số 15, Láng Hạ, Đống Đa', 'Số 25, Huỳnh Thúc Kháng, Đống Đa'],
            ['Số 8, Nguyễn Xiển, Thanh Xuân', 'Số 18, Lê Trọng Tấn, Thanh Xuân', 'Số 30, Nguyễn Quý Đức, Thanh Xuân'],
        ];

        $fields = [];
        foreach ($ownerUsers as $oi => $owner) {
            $sportPool = $sports;
            shuffle($sportPool);

            foreach ($fieldNames[$oi] as $fi => $fname) {
                $sport = $sportPool[$fi % count($sportPool)];
                $prices = [100000, 150000, 200000, 250000, 300000, 400000];
                $price = $prices[($fi + $oi * 2) % count($prices)];
                $fields[] = Field::create([
                    'owner_id' => $owner->id,
                    'sport_id' => $sport->id,
                    'name' => $fname,
                    'description' => "Sân {$fname} - " . $sport->name . ', đạt chuẩn chất lượng cao.',
                    'address' => $addresses[$oi][$fi],
                    'price_per_hour' => $price,
                    'open_time' => '06:00',
                    'close_time' => '22:00',
                    'status' => 'active',
                ]);
            }
        }

        // ── 6. Time slots (8 slots 06:00→22:00, 2h each) ──
        $slotRanges = [
            ['06:00', '08:00'],
            ['08:00', '10:00'],
            ['10:00', '12:00'],
            ['12:00', '14:00'],
            ['14:00', '16:00'],
            ['16:00', '18:00'],
            ['18:00', '20:00'],
            ['20:00', '22:00'],
        ];
        foreach ($fields as $field) {
            foreach ($slotRanges as [$start, $end]) {
                TimeSlot::create([
                    'field_id' => $field->id,
                    'start_time' => $start,
                    'end_time' => $end,
                    'is_active' => true,
                ]);
            }
        }

        // ── 7. Customers ──
        $customerUsers = [];
        $customerData = [
            ['Phạm Văn D',   'customer1@example.com', '0945678901', 'Hoàn Kiếm, Hà Nội'],
            ['Hoàng Thị E',  'customer2@example.com', '0956789012', 'Hai Bà Trưng, Hà Nội'],
            ['Đặng Văn F',   'customer3@example.com', '0967890123', 'Ba Đình, Hà Nội'],
            ['Vũ Thị G',     'customer4@example.com', '0978901234', 'Hoàng Mai, Hà Nội'],
            ['Bùi Văn H',    'customer5@example.com', '0989012345', 'Long Biên, Hà Nội'],
        ];
        foreach ($customerData as [$name, $email, $phone, $address]) {
            $customerUsers[] = User::create([
                'role_id' => $roles['customer']->id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // ── 8. Bookings + Payments ──
        $allSlots = TimeSlot::all()->groupBy('field_id');
        $now = now();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        foreach ($fields as $field) {
            $slots = $allSlots->get($field->id, collect());
            if ($slots->isEmpty()) continue;

            // 3 bookings per field: past (completed), today (confirmed/pending), future (pending)
            $bookingConfigs = [
                ['date' => $now->copy()->subDays(rand(1, 5)),  'status' => 'completed',   'payment' => true],
                ['date' => $now->copy()->format('Y-m-d'),      'status' => 'confirmed',   'payment' => true],
                ['date' => $now->copy()->addDays(rand(1, 7)),  'status' => 'pending',     'payment' => false],
            ];

            foreach ($bookingConfigs as $cfg) {
                $slot = $slots->random();
                $customer = $customerUsers[array_rand($customerUsers)];

                $booking = Booking::create([
                    'customer_id' => $customer->id,
                    'field_id' => $field->id,
                    'time_slot_id' => $slot->id,
                    'booking_date' => is_string($cfg['date']) ? $cfg['date'] : $cfg['date']->format('Y-m-d'),
                    'total_price' => $field->price_per_hour,
                    'status' => $cfg['status'],
                    'note' => $cfg['status'] === 'cancelled' ? 'Không thể đi được, hẹn lần sau' : null,
                    'confirmed_at' => $cfg['status'] === 'completed' || $cfg['status'] === 'confirmed' ? $now->copy()->subDay() : null,
                    'cancelled_at' => $cfg['status'] === 'cancelled' ? $now->copy()->subHours(rand(1, 12)) : null,
                ]);

                if ($cfg['payment']) {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $field->price_per_hour,
                        'method' => ['cash', 'bank_transfer', 'momo', 'vnpay'][array_rand(['cash', 'bank_transfer', 'momo', 'vnpay'])],
                        'status' => 'paid',
                        'transaction_code' => strtoupper('TXN-' . $booking->id . rand(1000, 9999)),
                        'paid_at' => $now->copy()->subHours(rand(1, 48)),
                    ]);
                }
            }
        }

        $this->command->info('✓ Seeded: ' . Role::count() . ' roles, ' . User::count() . ' users, '
            . Sport::count() . ' sports, ' . Field::count() . ' fields, '
            . TimeSlot::count() . ' time slots, ' . Booking::count() . ' bookings, '
            . Payment::count() . ' payments');
    }
}
