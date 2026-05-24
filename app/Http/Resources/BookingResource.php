<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'field' => new FieldResource($this->whenLoaded('field')),
            'time_slot' => new TimeSlotResource($this->whenLoaded('timeSlot')),
            'booking_date' => $this->booking_date?->format('Y-m-d'),
            'total_price' => (float) $this->total_price,
            'status' => $this->status,
            'note' => $this->note,
            'confirmed_at' => $this->confirmed_at,
            'cancelled_at' => $this->cancelled_at,
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'created_at' => $this->created_at,
        ];
    }
}
