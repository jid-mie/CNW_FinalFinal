<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory;

    // BẮT BUỘC khai báo dòng này để cho phép chèn dữ liệu qua form
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'image', 
        'badge', 
        'is_active'
    ];
}