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

    protected static function booted()
    {
        static::creating(function ($field) {
            if (empty($field->code)) {
                $field->code = static::generateUniqueCode($field->sport_id);
            }
        });

        static::updating(function ($field) {
            if (empty($field->code)) {
                $field->code = static::generateUniqueCode($field->sport_id);
            }
        });
    }

    public static function generateUniqueCode($sportId)
    {
        $sport = Sport::find($sportId);
        $prefix = 'SAN';
        if ($sport) {
            $mapping = [
                'bong-da' => 'SBD',
                'tennis' => 'TN',
                'cau-long' => 'BL',
                'bong-ro' => 'BR',
                'bong-chuyen' => 'BC',
                'bong-ban' => 'BB',
                'pickleball' => 'PB',
                'da-cau' => 'DC',
            ];
            $prefix = $mapping[$sport->slug] ?? strtoupper(substr($sport->slug, 0, 3));
        }

        $latest = static::where('code', 'LIKE', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $num = 1;
        if ($latest && preg_match('/-(\d+)$/', $latest->code, $matches)) {
            $num = intval($matches[1]) + 1;
        }

        $code = $prefix . '-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        
        while (static::where('code', $code)->exists()) {
            $num++;
            $code = $prefix . '-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }


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