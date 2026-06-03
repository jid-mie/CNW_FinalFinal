<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'bookings_count' => (int) ($this->bookings_count ?? $this->whenAggregated('bookings', 'id', 'count')),
            'total_spent' => (float) ($this->total_spent ?? 0),
            'last_booking_at' => $this->when($this->relationLoaded('bookings'), function () {
                return $this->bookings->last()?->created_at;
            }),
            'created_at' => $this->created_at,
        ];
    }
}
