<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $ownerRole = Role::updateOrCreate(['name' => 'owner'], ['display_name' => 'Owner']);
        $customerRole = Role::updateOrCreate(['name' => 'customer'], ['display_name' => 'Customer']);

        // 2. Default Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $owner = User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'role_id' => $ownerRole->id,
                'name' => 'Trần Quang Huy (Owner)',
                'password' => Hash::make('password'),
                'phone' => '0987654321',
                'address' => '123 Đường Lê Lợi, Quận 1, TP. HCM',
                'email_verified_at' => now(),
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'role_id' => $customerRole->id,
                'name' => 'Nguyễn Văn Nam',
                'password' => Hash::make('password'),
                'phone' => '0912345678',
                'address' => '456 Đường Nguyễn Huệ, Quận 1, TP. HCM',
                'email_verified_at' => now(),
            ]
        );

        // 3. Sports
        $football = Sport::updateOrCreate(
            ['slug' => 'bong-da'],
            [
                'name' => 'Bóng đá',
                'description' => 'Môn thể thao vua, chơi trên sân cỏ nhân tạo 5 người, 7 người hoặc 11 người.',
                'is_active' => true,
            ]
        );

        $tennis = Sport::updateOrCreate(
            ['slug' => 'tennis'],
            [
                'name' => 'Tennis',
                'description' => 'Chơi trên sân đất nện, sân cứng tiêu chuẩn thi đấu.',
                'is_active' => true,
            ]
        );

        // 4. Fields (Courts)
        $field1 = Field::updateOrCreate(
            ['code' => 'BD01'],
            [
                'owner_id' => $owner->id,
                'sport_id' => $football->id,
                'name' => 'Sân Bóng Đá Số 1',
                'description' => 'Sân cỏ nhân tạo 7 người, cỏ chất lượng cao, hệ thống đèn LED hiện đại.',
                'address' => '321 Bình Quới, Bình Thạnh, TP. HCM',
                'price_per_hour' => 300000,
                'open_time' => '06:00:00',
                'close_time' => '22:00:00',
                'status' => 'active',
            ]
        );

        $field2 = Field::updateOrCreate(
            ['code' => 'BD02'],
            [
                'owner_id' => $owner->id,
                'sport_id' => $football->id,
                'name' => 'Sân Bóng Đá Số 2',
                'description' => 'Sân cỏ nhân tạo 5 người, thích hợp đá giao lưu gia đình và bạn bè.',
                'address' => '321 Bình Quới, Bình Thạnh, TP. HCM',
                'price_per_hour' => 200000,
                'open_time' => '06:00:00',
                'close_time' => '22:00:00',
                'status' => 'active',
            ]
        );

        $field3 = Field::updateOrCreate(
            ['code' => 'TN01'],
            [
                'owner_id' => $owner->id,
                'sport_id' => $tennis->id,
                'name' => 'Sân Tennis Đất Nện VIP',
                'description' => 'Sân đất nện tiêu chuẩn quốc tế, có mái che ngoài trời.',
                'address' => '15 Đường 3/2, Quận 10, TP. HCM',
                'price_per_hour' => 500000,
                'open_time' => '05:00:00',
                'close_time' => '22:00:00',
                'status' => 'active',
            ]
        );

        // 5. Time Slots for Sân 1
        $slotsData = [
            ['start_time' => '06:00:00', 'end_time' => '07:30:00'],
            ['start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['start_time' => '15:30:00', 'end_time' => '17:00:00'],
            ['start_time' => '17:00:00', 'end_time' => '18:30:00'],
            ['start_time' => '18:30:00', 'end_time' => '20:00:00'],
            ['start_time' => '20:00:00', 'end_time' => '21:30:00'],
        ];

        $timeSlotsField1 = [];
        foreach ($slotsData as $slot) {
            $timeSlotsField1[] = TimeSlot::updateOrCreate(
                ['field_id' => $field1->id, 'start_time' => $slot['start_time']],
                ['end_time' => $slot['end_time'], 'is_active' => true]
            );
        }

        $timeSlotsField2 = [];
        foreach ($slotsData as $slot) {
            $timeSlotsField2[] = TimeSlot::updateOrCreate(
                ['field_id' => $field2->id, 'start_time' => $slot['start_time']],
                ['end_time' => $slot['end_time'], 'is_active' => true]
            );
        }

        $timeSlotsField3 = [];
        foreach ($slotsData as $slot) {
            $timeSlotsField3[] = TimeSlot::updateOrCreate(
                ['field_id' => $field3->id, 'start_time' => $slot['start_time']],
                ['end_time' => $slot['end_time'], 'is_active' => true]
            );
        }

        // 6. Bookings & Payments
        // Booking 1: Today, Pending (Sân 1, slot 17:00 - 18:30)
        $booking1 = Booking::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'field_id' => $field1->id,
                'time_slot_id' => $timeSlotsField1[3]->id,
                'booking_date' => today(),
            ],
            [
                'total_price' => 450000, // 1.5 hours * 300k
                'status' => 'pending',
                'note' => 'Cần thuê thêm 2 áo tập.',
            ]
        );

        // Booking 2: Today, Confirmed (Sân 2, slot 18:30 - 20:00)
        $booking2 = Booking::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'field_id' => $field2->id,
                'time_slot_id' => $timeSlotsField2[4]->id,
                'booking_date' => today(),
            ],
            [
                'total_price' => 300000,
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $booking2->id],
            [
                'amount' => 300000,
                'method' => 'bank_transfer',
                'status' => 'paid',
                'transaction_code' => 'TXN' . strtoupper(Str::random(10)),
                'paid_at' => now(),
            ]
        );

        // Booking 3: Yesterday, Completed (Sân 3, slot 20:00 - 21:30)
        $booking3 = Booking::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'field_id' => $field3->id,
                'time_slot_id' => $timeSlotsField3[5]->id,
                'booking_date' => today()->subDay(),
            ],
            [
                'total_price' => 750000, // 1.5 * 500k
                'status' => 'completed',
                'confirmed_at' => today()->subDay(),
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $booking3->id],
            [
                'amount' => 750000,
                'method' => 'cash',
                'status' => 'paid',
                'paid_at' => today()->subDay(),
            ]
        );

        // Booking 4: Tomorrow, Pending (Sân 1, slot 18:30 - 20:00)
        $booking4 = Booking::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'field_id' => $field1->id,
                'time_slot_id' => $timeSlotsField1[4]->id,
                'booking_date' => today()->addDay(),
            ],
            [
                'total_price' => 450000,
                'status' => 'pending',
            ]
        );

        // Booking 5: Current Month (completed, but unpaid/cash - should be included in monthly revenue)
        $booking5 = Booking::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'field_id' => $field2->id,
                'time_slot_id' => $timeSlotsField2[3]->id,
                'booking_date' => today()->startOfMonth()->addDays(2),
            ],
            [
                'total_price' => 300000,
                'status' => 'completed',
                'confirmed_at' => today()->startOfMonth()->addDays(2),
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $booking5->id],
            [
                'amount' => 300000,
                'method' => 'cash',
                'status' => 'unpaid',
            ]
        );
    }
}
