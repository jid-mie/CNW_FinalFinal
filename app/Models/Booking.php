<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'field_id',
        'customer_id', // Đổi tên cột cho khớp chuẩn Git
        'status',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // 🌟 ĐÃ SỬA: Liên kết lấy thông tin tài khoản khách hàng qua customer_id
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}