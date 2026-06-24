<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Sport;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
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
            'admin' => Role::create(['name' => 'admin',    'display_name' => 'Admin']),
            'owner' => Role::create(['name' => 'owner',    'display_name' => 'Chủ sân']),
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
                'image_url' => 'uploads/sports/'.$slug.'.png',
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
                    'code' => 'SBD-'.str_pad(count($fields) + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $fname,
                    'description' => "Sân {$fname} - ".$sport->name.', đạt chuẩn thi đấu.',
                    'address' => $addresses[$oi][$fi],
                    'price_per_hour' => $price,
                    'open_time' => '06:00',
                    'close_time' => '22:00',
                    'image_url' => 'uploads/fields/san-'.$sport->slug.'.png',
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

        // ── 8. Bookings + Payments (Bản MA TRẬN dữ liệu động - Tự động tính toán theo ngày hiện tại) ──
        $allSlots = TimeSlot::all()->groupBy('field_id');
        $customerCount = count($customerUsers);
        $fieldCount = count($fields);

        $bookingKeys = [];
        $bookingsCount = 120; // Tạo 120 bookings mẫu cho nhiều trang và biểu đồ đẹp mắt
        $now = Carbon::now();

        for ($i = 0; $i < $bookingsCount; $i++) {
            // Phân bổ ngày đặt sân:
            // - 70% trong quá khứ (-30 ngày tới -1 ngày) -> làm sạch lịch sử và tạo doanh thu giả lập
            // - 10% trong ngày hôm nay (0) -> test trực quan trên dashboard
            // - 20% trong tương lai (+1 ngày tới +7 ngày) -> test đặt trước, duyệt lịch sân
            $randVal = rand(1, 100);
            if ($randVal <= 70) {
                $daysOffset = rand(-30, -1);
                $status = rand(1, 10) <= 8 ? 'completed' : 'cancelled';
                $p_status = $status === 'completed' ? 'paid' : 'refunded';
            } elseif ($randVal <= 80) {
                $daysOffset = 0;
                $statusRand = rand(1, 10);
                if ($statusRand <= 4) {
                    $status = 'completed';
                    $p_status = 'paid';
                } elseif ($statusRand <= 7) {
                    $status = 'confirmed';
                    $p_status = 'paid';
                } elseif ($statusRand <= 9) {
                    $status = 'pending';
                    $p_status = 'pending';
                } else {
                    $status = 'cancelled';
                    $p_status = 'unpaid';
                }
            } else {
                $daysOffset = rand(1, 7);
                $statusRand = rand(1, 10);
                if ($statusRand <= 6) {
                    $status = 'confirmed';
                    $p_status = 'paid';
                } elseif ($statusRand <= 9) {
                    $status = 'pending';
                    $p_status = 'pending';
                } else {
                    $status = 'cancelled';
                    $p_status = 'unpaid';
                }
            }

            $bookingDate = (clone $now)->addDays($daysOffset)->format('Y-m-d');

            // Chọn ngẫu nhiên 1 sân
            $field = $fields[$i % $fieldCount];
            $slots = $allSlots->get($field->id, collect());
            if ($slots->isEmpty()) {
                continue;
            }

            // Tìm slot trống của sân đó trong ngày cụ thể để tránh lỗi UNIQUE overlap
            $slot = null;
            foreach ($slots->shuffle() as $s) {
                $key = "{$field->id}-{$bookingDate}-{$s->id}";
                if (!isset($bookingKeys[$key])) {
                    $slot = $s;
                    $bookingKeys[$key] = true;
                    break;
                }
            }

            if (!$slot) {
                // Nếu sân này đã kín lịch vào ngày này, bỏ qua
                continue;
            }

            $customer = $customerUsers[rand(0, $customerCount - 1)];

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'field_id' => $field->id,
                'time_slot_id' => $slot->id,
                'booking_date' => $bookingDate,
                'total_price' => $field->price_per_hour,
                'status' => $status,
                'note' => 'Hóa đơn giả lập dòng tiền số ' . ($i + 1),
                'confirmed_at' => in_array($status, ['confirmed', 'completed']) ? (clone $now)->addDays($daysOffset)->subHours(rand(1, 5)) : null,
                'cancelled_at' => $status === 'cancelled' ? (clone $now)->addDays($daysOffset)->subHours(rand(1, 2)) : null,
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $field->price_per_hour,
                'method' => 'bank_transfer',
                'status' => $p_status,
                'transaction_code' => 'TXN-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'paid_at' => $p_status === 'paid' ? Carbon::parse($bookingDate . ' ' . sprintf('%02d:%02d:00', rand(6, 21), rand(0, 59))) : null,
            ]);
        }

        $this->command->info('✓ Seeded thành công ' . count($bookingKeys) . ' dòng dữ liệu đặt sân động thực tế!');
    }
}
