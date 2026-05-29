<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    // Gom đầy đủ tất cả các trường dữ liệu của cả bạn và nhóm
    protected $fillable = [
        'owner_id', 
        'sport_id', 
        'name', 
        'code', 
        'description',
        'address', 
        'price_per_hour', 
        'open_time', 
        'close_time',
        'image', 
        'image_url', 
        'status',
    ];

    // Giữ nguyên bộ ép kiểu dữ liệu chuẩn của nhóm để không lỗi định dạng giờ giấc
    protected function casts(): array
    {
        return [
            'price_per_hour' => 'decimal:2',
            'open_time' => 'datetime:H:i',
            'close_time' => 'datetime:H:i',
        ];
    }

    // 👑 Mối quan hệ: Sân thuộc về một Chủ sân (User)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ⚽ Mối quan hệ: Sân thuộc về một Môn thể thao (Sport)
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    // 🕒 Mối quan hệ: Một sân có nhiều Khung giờ đặt khác nhau
    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    // 📅 Mối quan hệ: Một sân có nhiều Đơn đặt lịch (Bookings)
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}