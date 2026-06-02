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
            'booking_date' => $this->booking_date,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'note' => $this->note,
            'confirmed_at' => $this->confirmed_at,
            'cancelled_at' => $this->cancelled_at,
            'field' => new FieldResource($this->whenLoaded('field')),
            'time_slot' => [
                'id' => $this->timeSlot?->id,
                'start_time' => $this->timeSlot?->start_time,
                'end_time' => $this->timeSlot?->end_time,
            ],
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
