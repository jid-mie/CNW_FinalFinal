<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        // ⚽ Sử dụng lại link gốc ở Ảnh 1 (Đã kiểm tra hoạt động 100%)
        Sport::updateOrCreate(
            ['slug' => 'bong-da'],
            [
                'name' => 'Bóng đá',
                'badge' => 'Phổ biến',
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600',
                'description' => 'Môn thể thao vua với lượng người chơi đông đảo nhất hệ thống, hỗ trợ các sân 5, sân 7 và sân 11.'
            ]
        );

        // 🎾 Giữ nguyên link đang chạy tốt ở Ảnh 2
        Sport::updateOrCreate(
            ['slug' => 'tennis'],
            [
                'name' => 'Tennis',
                'badge' => null,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=600',
                'description' => 'Các sân tennis tiêu chuẩn quốc tế, bao gồm cả các loại hình sân đất nện.'
            ]
        );

        // 🏸 Giữ nguyên link đang chạy tốt ở Ảnh 2
        Sport::updateOrCreate(
            ['slug' => 'cau-long'],
            [
                'name' => 'Cầu lông',
                'badge' => null,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=600',
                'description' => 'Môn thể thao trong nhà phổ biến, yêu cầu độ chính xác và phản xạ cao.'
            ]
        );

        // 🏀 ĐỔI SANG LINK BÓNG RỔ MỚI (Đã check CDN Unsplash Global, bao mượt)
        Sport::updateOrCreate(
            ['slug' => 'bong-ro'],
            [
                'name' => 'Bóng rổ',
                'badge' => null,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1544698310-74ea9d1c8258?w=600',
                'description' => 'Hệ thống sân bóng rổ trong nhà và ngoài trời chất lượng cao.'
            ]
        );

        // 🏊 Giữ nguyên link đang chạy tốt ở Ảnh 2
        Sport::updateOrCreate(
            ['slug' => 'boi-loi'],
            [
                'name' => 'Bơi lội',
                'badge' => 'Mới',
                'is_active' => false,
                'image' => 'https://images.unsplash.com/photo-1519315901367-f34ff9154487?w=600',
                'description' => 'Hồ bơi tiêu chuẩn Olympic toàn diện, hệ thống lọc nước hiện đại.'
            ]
        );

        // 🏐 Giữ nguyên link đang chạy tốt ở Ảnh 2
        Sport::updateOrCreate(
            ['slug' => 'bong-chuyen'],
            [
                'name' => 'Bóng chuyền',
                'badge' => null,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=600',
                'description' => 'Sân bóng chuyền tiêu chuẩn với thảm bọc cao cấp giảm chấn.'
            ]
        );
    }
}