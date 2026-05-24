<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'status' => $this->status,
            'transaction_code' => $this->transaction_code,
            'paid_at' => $this->paid_at,
            'note' => $this->note,
        ];
    }
}
