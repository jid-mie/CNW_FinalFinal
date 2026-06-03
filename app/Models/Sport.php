<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sport extends Model
{
    use HasFactory, SoftDeletes;

    // Hòa trộn đầy đủ các trường của cả bạn và nhóm để form CRUD chạy không bị lỗi
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'image', 
        'image_url',
        'badge', 
        'is_active'
    ];

    // Bộ ép kiểu dữ liệu chuẩn từ nhánh main
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ⚽ Mối quan hệ: Một môn thể thao có thể có nhiều Sân chi tiết (Fields)
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}