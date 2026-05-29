<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Hòa trộn đầy đủ các trường của cả bạn và nhóm để tránh lỗi Mass Assignment
    protected $fillable = [
        'booking_id', 
        'amount', 
        'method',           // Tên cột của nhóm
        'payment_method',   // Tên cột của bạn
        'status',
        'transaction_code', // Tên cột của nhóm
        'payment_code',     // Tên cột của bạn
        'paid_at', 
        'note',
    ];

    // Bộ ép kiểu dữ liệu chuẩn từ nhánh main để tính toán hóa đơn chính xác
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    // 💳 Mối quan hệ: Một hóa đơn thanh toán sẽ thuộc về một lịch đặt sân cụ thể
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}