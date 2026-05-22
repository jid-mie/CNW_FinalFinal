<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get start time formatted as H:i
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return date('H:i', strtotime($this->start_time));
    }

    /**
     * Get end time formatted as H:i
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return date('H:i', strtotime($this->end_time));
    }

    /**
     * Get formatted slot string e.g. "08:00 - 09:30"
     */
    public function getFormattedSlotAttribute(): string
    {
        return $this->formatted_start_time . ' - ' . $this->formatted_end_time;
    }
}
