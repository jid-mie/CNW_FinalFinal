<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'sport' => new SportResource($this->whenLoaded('sport')),
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'address' => $this->address,
            'price_per_hour' => (float) $this->price_per_hour,
            'open_time' => $this->open_time?->format('H:i'),
            'close_time' => $this->close_time?->format('H:i'),
            'image' => $this->image_url ?? $this->image,
            'status' => $this->status,
            'time_slots_count' => $this->whenCounted('timeSlots'),
            'bookings_count' => $this->whenCounted('bookings'),
            'time_slots' => TimeSlotResource::collection($this->whenLoaded('timeSlots')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
