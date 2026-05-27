<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use App\Models\Field;
use Carbon\Carbon;

class FieldBookingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lấy hoặc tạo Role và tài khoản Chủ sân mẫu
        $ownerRole = Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Owner']);
        $owners = [];
        $ownerNames = ['Nguyễn Văn A', 'Trần Thị B', 'Lê Hoàng C', 'Phạm Minh D'];
        foreach ($ownerNames as $index => $name) {
            $owners[] = User::firstOrCreate(
                ['email' => 'owner' . ($index + 1) . '@play.com'],
                ['name' => $name, 'password' => bcrypt('123456'), 'role_id' => $ownerRole->id]
            );
        }

        // 2. Lấy hoặc tạo Role và danh sách Khách hàng mẫu
        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Customer']);
        $customers = [];
        $customerNames = ['Nguyễn Văn An', 'Trần Thị Hoa', 'Lê Minh', 'Phạm Duy', 'Hoàng Bảo', 'Vũ Ngọc'];
        foreach ($customerNames as $index => $name) {
            $customers[] = User::firstOrCreate(
                ['email' => 'customer' . ($index + 1) . '@play.com'],
                ['name' => $name, 'password' => bcrypt('123456'), 'role_id' => $customerRole->id]
            );
        }

        // 3. Lấy hoặc tạo các môn thể thao làm gốc liên kết
        $bongDa  = Sport::firstOrCreate(['name' => 'Bóng đá'], ['slug' => 'bong-da', 'is_active' => true]);
        $tennis  = Sport::firstOrCreate(['name' => 'Tennis'], ['slug' => 'tennis', 'is_active' => true]);
        $cauLong = Sport::firstOrCreate(['name' => 'Cầu lông'], ['slug' => 'cau-long', 'is_active' => true]);
        $bongRo  = Sport::firstOrCreate(['name' => 'Bóng rổ'], ['slug' => 'bong-ro', 'is_active' => true]);

        // 4. Khởi tạo khung giờ toàn hệ thống dùng chung từ bản Git
        $timeSlotIds = [];
        if (Schema::hasTable('time_slots')) {
            DB::table('time_slots')->delete();
            $slots = [
                ['start_time' => '08:00:00', 'end_time' => '10:00:00'],
                ['start_time' => '10:00:00', 'end_time' => '12:00:00'],
                ['start_time' => '14:00:00', 'end_time' => '16:00:00'],
                ['start_time' => '16:00:00', 'end_time' => '18:00:00'],
                ['start_time' => '18:00:00', 'end_time' => '20:00:00'],
                ['start_time' => '20:00:00', 'end_time' => '22:00:00'],
            ];
            foreach ($slots as $slot) {
                $timeSlotIds[] = DB::table('time_slots')->insertGetId(array_merge($slot, [
                    'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
                ]));
            }
        }

        // 5. 🚀 ĐÃ MỞ RỘNG: Danh sách 16 sân thể thao phong phú để kích hoạt hiển thị chuyển Trang 2
        $fields = [
            ['code' => 'SBD-001', 'name' => 'Sân Bóng Đá Mini Thủy Lợi - Sân A', 'sport_id' => $bongDa->id, 'owner_id' => $owners[0]->id, 'address' => 'Đống Đa, Hà Nội', 'price_per_hour' => 300000, 'status' => 'active'],
            ['code' => 'SBD-002', 'name' => 'Sân Bóng Đá Mini Thủy Lợi - Sân B', 'sport_id' => $bongDa->id, 'owner_id' => $owners[0]->id, 'address' => 'Đống Đa, Hà Nội', 'price_per_hour' => 300000, 'status' => 'active'],
            ['code' => 'SBD-003', 'name' => 'Sân Bóng Đá Mỹ Đình - Sân Đại', 'sport_id' => $bongDa->id, 'owner_id' => $owners[1]->id, 'address' => 'Nam Từ Liêm, Hà Nội', 'price_per_hour' => 550000, 'status' => 'active'],
            ['code' => 'TN-005', 'name' => 'Sân Tennis Mỹ Đình - Sân Chính', 'sport_id' => $tennis->id, 'owner_id' => $owners[1]->id, 'address' => 'Nam Từ Liêm, Hà Nội', 'price_per_hour' => 250000, 'status' => 'active'],
            ['code' => 'TN-006', 'name' => 'Sân Tennis Khách Sạn Daewoo', 'sport_id' => $tennis->id, 'owner_id' => $owners[2]->id, 'address' => 'Ba Đình, Hà Nội', 'price_per_hour' => 400000, 'status' => 'active'],
            ['code' => 'BL-012', 'name' => 'Cầu Lông Thăng Long - Thảm 1', 'sport_id' => $cauLong->id, 'owner_id' => $owners[2]->id, 'address' => 'Cầu Giấy, Hà Nội', 'price_per_hour' => 150000, 'status' => 'maintenance'],
            ['code' => 'BL-013', 'name' => 'Cầu Lông Thăng Long - Thảm 2', 'sport_id' => $cauLong->id, 'owner_id' => $owners[2]->id, 'address' => 'Cầu Giấy, Hà Nội', 'price_per_hour' => 150000, 'status' => 'active'],
            ['code' => 'SBD-024', 'name' => 'Sân Bóng Đá Đầm Sen', 'sport_id' => $bongDa->id, 'owner_id' => $owners[3]->id, 'address' => 'Quận 11, TP. HCM', 'price_per_hour' => 280000, 'status' => 'inactive'],
            ['code' => 'BR-003', 'name' => 'Sân Bóng Rổ Canvas Quận 7', 'sport_id' => $bongRo->id, 'owner_id' => $owners[0]->id, 'address' => 'Quận 7, TP. HCM', 'price_per_hour' => 200000, 'status' => 'active'],
            ['code' => 'BR-004', 'name' => 'Sân Bóng Rổ Bách Khoa', 'sport_id' => $bongRo->id, 'owner_id' => $owners[1]->id, 'address' => 'Hai Bà Trưng, Hà Nội', 'price_per_hour' => 180000, 'status' => 'active'],
            ['code' => 'BL-015', 'name' => 'Cầu Lông Bình Thạnh', 'sport_id' => $cauLong->id, 'owner_id' => $owners[1]->id, 'address' => 'Bình Thạnh, TP. HCM', 'price_per_hour' => 180000, 'status' => 'active'],
            ['code' => 'BL-016', 'name' => 'Cầu Lông Cầu Giấy Premium', 'sport_id' => $cauLong->id, 'owner_id' => $owners[2]->id, 'address' => 'Cầu Giấy, Hà Nội', 'price_per_hour' => 200000, 'status' => 'active'],
            ['code' => 'TN-008', 'name' => 'Sân Tennis Cầu Giấy Đất Nện', 'sport_id' => $tennis->id, 'owner_id' => $owners[3]->id, 'address' => 'Cầu Giấy, Hà Nội', 'price_per_hour' => 320000, 'status' => 'active'],
            ['code' => 'SBD-005', 'name' => 'Sân Bóng Đá Bách Khoa Cỏ Tự Nhiên', 'sport_id' => $bongDa->id, 'owner_id' => $owners[1]->id, 'address' => 'Hai Bà Trưng, Hà Nội', 'price_per_hour' => 450000, 'status' => 'active'],
            ['code' => 'BR-005', 'name' => 'Sân Bóng Rổ Phan Đình Phùng', 'sport_id' => $bongRo->id, 'owner_id' => $owners[0]->id, 'address' => 'Quận 3, TP. HCM', 'price_per_hour' => 220000, 'status' => 'active'],
            ['code' => 'BL-018', 'name' => 'Cầu Lông Kỳ Hòa', 'sport_id' => $cauLong->id, 'owner_id' => $owners[1]->id, 'address' => 'Quận 10, TP. HCM', 'price_per_hour' => 170000, 'status' => 'active']
        ];

        $bookingColumns = Schema::getColumnListing('bookings');
        $paymentColumns = Schema::getColumnListing('payments');

        // 🔍 ĐOẠN ĐỘC QUYỀN: Quét cấu trúc SQLite để tìm từ khóa trạng thái chuẩn từ Git nhóm bạn
        $successStatus = 'success'; $pendingStatus = 'pending'; $refundedStatus = 'refunded';
        $sqliteInfo = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='payments'");
        if (!empty($sqliteInfo) && isset($sqliteInfo[0]->sql)) {
            $tableSql = $sqliteInfo[0]->sql;
            if (preg_match('/CHECK\s*\(\s*["\']?status["\']?\s+IN\s*\((.*?)\)\s*\)/i', $tableSql, $matches)) {
                $cleanValues = str_replace(["'", '"', ' '], '', $matches[1]);
                $allowedArr = explode(',', $cleanValues);
                
                foreach (['paid', 'completed', 'success', 'approved', 'Success'] as $v) { if (in_array($v, $allowedArr)) { $successStatus = $v; break; } }
                foreach (['pending', 'unpaid', 'processing', 'Pending'] as $v) { if (in_array($v, $allowedArr)) { $pendingStatus = $v; break; } }
                foreach (['refunded', 'failed', 'cancelled', 'Refunded'] as $v) { if (in_array($v, $allowedArr)) { $refundedStatus = $v; break; } }
            }
        }

        foreach ($fields as $index => $fieldData) {
            $field = Field::updateOrCreate(['code' => $fieldData['code']], $fieldData);

            // 6. BIÊN TẬP DỮ LIỆU BOOKING
            $bookingData = [];
            if (in_array('field_id', $bookingColumns)) $bookingData['field_id'] = $field->id;
            if (in_array('time_slot_id', $bookingColumns) && count($timeSlotIds) > 0) {
                $bookingData['time_slot_id'] = $timeSlotIds[$index % count($timeSlotIds)];
            }
            if (in_array('customer_id', $bookingColumns)) {
                $bookingData['customer_id'] = $customers[$index % count($customers)]->id;
            }
            if (in_array('booking_date', $bookingColumns)) {
                $bookingData['booking_date'] = Carbon::now()->addDays(rand(1, 4))->toDateString();
            }
            if (in_array('total_amount', $bookingColumns)) $bookingData['total_amount'] = $field->price_per_hour * 2;
            if (in_array('total_price', $bookingColumns)) $bookingData['total_price'] = $field->price_per_hour * 2;
            if (in_array('price', $bookingColumns)) $bookingData['price'] = $field->price_per_hour * 2;
            if (in_array('status', $bookingColumns)) $bookingData['status'] = 'confirmed';

            $bookingId = DB::table('bookings')->insertGetId(array_merge($bookingData, [
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]));

            // 7. BIÊN TẬP DỮ LIỆU THANH TOÁN THEO CỘT ĐÃ ĐƯỢC GIẢI MÃ
            $paymentData = [];
            if (in_array('booking_id', $paymentColumns)) $paymentData['booking_id'] = $bookingId;
            if (in_array('amount', $paymentColumns)) $paymentData['amount'] = $field->price_per_hour * 2;
            if (in_array('payment_method', $paymentColumns)) {
                $paymentData['payment_method'] = ['MoMo', 'VNPay', 'Tiền mặt', 'Chuyển khoản'][$index % 4];
            }
            
            // Áp dụng từ khóa trạng thái chuẩn xác đã dò được từ Git
            if (in_array('status', $paymentColumns)) {
                $paymentData['status'] = [$successStatus, $pendingStatus, $successStatus, $refundedStatus, $successStatus, $successStatus][$index % 6];
            }
            if (in_array('paid_at', $paymentColumns)) $paymentData['paid_at'] = Carbon::now()->subHours(rand(1, 24));

            DB::table('payments')->insert(array_merge($paymentData, [
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]));
        }
    }
}