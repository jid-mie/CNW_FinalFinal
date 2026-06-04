<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $customer_id
 * @property int $field_id
 * @property int $time_slot_id
 * @property \Illuminate\Support\Carbon $booking_date
 * @property float $total_price
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\Field $field
 * @property-read \App\Models\TimeSlot $timeSlot
 * @property-read \App\Models\Payment|null $payment
 */
class Booking extends Model
{
    use HasFactory;

    // Giữ nguyên đầy đủ các trường dữ liệu từ nhánh chính của nhóm
    protected $fillable = [
        'customer_id', 
        'field_id', 
        'time_slot_id', 
        'booking_date',
        'total_price', 
        'status', 
        'note', 
        'confirmed_at', 
        'cancelled_at',
    ];

    // Ép kiểu dữ liệu chuẩn để tính toán không bị lỗi định dạng
    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
            'total_price' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // 🌟 Mối quan hệ lấy thông tin tài khoản khách hàng đặt sân
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // 🏢 Mối quan hệ lấy thông tin Sân thể thao
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // 🕒 Mối quan hệ lấy Khung giờ đặt sân
    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    // 💳 Mối quan hệ lấy Hóa đơn thanh toán tương ứng
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}