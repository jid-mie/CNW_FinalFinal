<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\FieldResource;
use App\Http\Resources\TimeSlotResource;
use App\Models\Field;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Field::where('status', 'active');

        if ($request->has('sport_id')) {
            $query->where('sport_id', $request->sport_id);
        }

        $fields = $query->with('sport')->get();

        return $this->successResponse(
            FieldResource::collection($fields),
            'Fields retrieved successfully'
        );
    }

    public function show(Field $field): JsonResponse
    {
        if ($field->status !== 'active') {
            return $this->errorResponse('Field not found', 404);
        }

        return $this->successResponse(
            new FieldResource($field->load('sport', 'owner')),
            'Field retrieved successfully'
        );
    }

    public function timeSlots(Request $request, Field $field): JsonResponse
    {
        if ($field->status !== 'active') {
            return $this->errorResponse('Field not found', 404);
        }

        $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        $bookingDate = $request->date;

        $timeSlots = $field->timeSlots()
            ->where('is_active', true)
            ->with([
                'bookings' => function ($query) use ($bookingDate) {
                    $query->where('booking_date', $bookingDate)
                        ->whereIn('status', ['pending', 'confirmed']);
                },
            ])
            ->get()
            ->map(function ($slot) use ($bookingDate, $field) {
                $isBooked = $slot->bookings->count() > 0;

                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'is_available' => ! $isBooked,
                    'field_id' => $field->id,
                    'booking_date' => $bookingDate,
                ];
            });

        return $this->successResponse(
            $timeSlots,
            'Available time slots retrieved successfully'
        );
    }
}
