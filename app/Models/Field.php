<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    protected $fillable = [
        'code',           // Đổi từ field_code thành code giống nhóm
        'name',
        'sport_id',
        'owner_id',       // Đổi từ owner_name thành owner_id giống nhóm
        'address',
        'price_per_hour',
        'open_time',
        'close_time',
        'image',
        'status',
    ];

    // Mối quan hệ: Sân thuộc về một Môn thể thao
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    // Mối quan hệ bổ sung: Sân thuộc về một Chủ sân (User)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}