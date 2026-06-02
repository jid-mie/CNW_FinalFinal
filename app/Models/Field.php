<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
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
    'status',
])]
class Field extends Model
{
    use HasFactory, SoftDeletes;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    protected function casts(): array
    {
        return [
            'price_per_hour' => 'decimal:2',
            'open_time' => 'datetime:H:i:s',
            'close_time' => 'datetime:H:i:s',
        ];
    }
}
