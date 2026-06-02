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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'price_per_hour' => $this->price_per_hour,
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
            'image' => $this->image,
            'status' => $this->status,
            'sport' => new SportResource($this->whenLoaded('sport')),
            'owner' => new UserResource($this->whenLoaded('owner')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
