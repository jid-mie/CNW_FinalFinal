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
                'image_url' => 'uploads/sports/' . $slug . '.png',
                'is_active' => true,
            ]);
        }

        // ── 5. Fields ──
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
                // Chỉnh giá sân xuống 100k và 200k để tiện test giao dịch
                $prices = [100000, 200000];
                $price = $prices[($fi + $oi) % count($prices)];
                $fields[] = Field::create([
                    'owner_id' => $owner->id,
                    'sport_id' => $sport->id,
                    'code' => 'SBD-' . str_pad(count($fields) + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $fname,
                    'description' => "Sân {$fname} - " . $sport->name . ', đạt chuẩn thi đấu.',
                    'address' => $addresses[$oi][$fi],
                    'price_per_hour' => $price,
                    'open_time' => '06:00',
                    'close_time' => '22:00',
                    'image_url' => 'uploads/fields/san-' . $sport->slug . '.png',
                    'status' => ($fi == 2) ? 'maintenance' : 'active', // Tạo sẵn một vài sân bảo trì
                ]);
            }
        }

        // ── 6. Time slots ──
        $slotRanges = [
            ['06:00', '08:00'], ['08:00', '10:00'], ['10:00', '12:00'], ['12:00', '14:00'],
            ['14:00', '16:00'], ['16:00', '18:00'], ['18:00', '20:00'], ['20:00', '22:00'],
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

        // ── 8. Bookings + Payments (Bản MA TRẬN 35 kịch bản - Bảo đảm chia đều 4 trang) ──
        $allSlots = TimeSlot::all()->groupBy('field_id');
        $customerCount = count($customerUsers);
        $fieldCount = count($fields);

        $scenarios = [
            // Ngày 20/05/2026
            ['date' => '2026-05-20', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-20', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-20', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-20', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 21/05/2026
            ['date' => '2026-05-21', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-21', 'status' => 'cancelled', 'method' => 'bank_transfer', 'p_status' => 'refunded'],
            ['date' => '2026-05-21', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 22/05/2026
            ['date' => '2026-05-22', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-22', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-22', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 23/05/2026
            ['date' => '2026-05-23', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-23', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-23', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 24/05/2026
            ['date' => '2026-05-24', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-24', 'status' => 'cancelled', 'method' => 'bank_transfer', 'p_status' => 'refunded'],
            ['date' => '2026-05-24', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 25/05/2026
            ['date' => '2026-05-25', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-25', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-25', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 26/05/2026
            ['date' => '2026-05-26', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-26', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-26', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            // Ngày 27/05/2026
            ['date' => '2026-05-27', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-27', 'status' => 'cancelled', 'method' => 'bank_transfer', 'p_status' => 'refunded'],
            ['date' => '2026-05-27', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 28/05/2026 (Mốc hôm nay)
            ['date' => '2026-05-28', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-28', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-28', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            // Ngày 29/05/2026
            ['date' => '2026-05-29', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-29', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-29', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            // Ngày 30/05/2026
            ['date' => '2026-05-30', 'status' => 'cancelled', 'method' => 'bank_transfer', 'p_status' => 'refunded'],
            ['date' => '2026-05-30', 'status' => 'pending',   'method' => 'bank_transfer', 'p_status' => 'pending'],
            ['date' => '2026-05-30', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
            ['date' => '2026-05-30', 'status' => 'completed', 'method' => 'bank_transfer', 'p_status' => 'paid'],
        ];

        foreach ($scenarios as $index => $scene) {
            $field = $fields[$index % $fieldCount];
            $customer = $customerUsers[$index % $customerCount];
            $slots = $allSlots->get($field->id, collect());
            $slot = $slots->isNotEmpty() ? $slots->random() : TimeSlot::first();

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'field_id' => $field->id,
                'time_slot_id' => $slot->id,
                'booking_date' => $scene['date'],
                'total_price' => $field->price_per_hour,
                'status' => $scene['status'],
                'note' => 'Hóa đơn giả lập dòng tiền số ' . ($index + 1),
                'confirmed_at' => $scene['status'] !== 'pending' ? now() : null,
                'cancelled_at' => $scene['status'] === 'cancelled' ? now() : null,
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $field->price_per_hour,
                'method' => $scene['method'], 
                'status' => $scene['p_status'], 
                'transaction_code' => 'TXN-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'paid_at' => $scene['p_status'] === 'paid' ? \Carbon\Carbon::parse($scene['date'] . ' 16:45:00') : null,
            ]);
        }

        $this->command->info('✓ Seeded thành công 35 dòng dữ liệu mẫu phân trang!');
    }
}