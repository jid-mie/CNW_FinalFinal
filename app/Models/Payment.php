<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_code',
        'booking_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
    ];

    // Mối quan hệ: Một hóa đơn thanh toán sẽ thuộc về một lịch đặt sân cụ thể
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}