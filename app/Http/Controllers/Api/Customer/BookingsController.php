<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StoreBookingRequest;
use App\Http\Requests\Api\Customer\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::where('customer_id', $request->user()->id)
            ->with(['field', 'timeSlot', 'payment'])
            ->latest()
            ->paginate(10);

        return $this->successResponse(
            BookingResource::collection($bookings),
            'Bookings retrieved successfully'
        );
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $field = Field::find($request->field_id);
        if (! $field || $field->status !== 'active') {
            return $this->errorResponse('Field not found or inactive', 404);
        }

        $timeSlot = TimeSlot::find($request->time_slot_id);
        if (! $timeSlot || ! $timeSlot->is_active) {
            return $this->errorResponse('Time slot not found or inactive', 404);
        }

        if ($timeSlot->field_id !== $field->id) {
            return $this->errorResponse('Time slot does not belong to this field', 422);
        }

        $existingBooking = Booking::where('field_id', $field->id)
            ->where('time_slot_id', $timeSlot->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return $this->errorResponse('This time slot is already booked', 409);
        }

        $totalPrice = $field->price_per_hour;

        try {
            $booking = Booking::create([
                'customer_id' => $request->user()->id,
                'field_id' => $field->id,
                'time_slot_id' => $timeSlot->id,
                'booking_date' => $request->booking_date,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'note' => $request->note,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            if ($sqlState === '23000' || $sqlState === '23505' || str_contains($e->getMessage(), 'UNIQUE constraint failed') || str_contains($e->getMessage(), 'Duplicate entry')) {
                return $this->errorResponse('This time slot is already booked', 409);
            }
            throw $e;
        }

        return $this->successResponse(
            new BookingResource($booking->load('field', 'timeSlot')),
            'Booking created successfully',
            201
        );
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        return $this->successResponse(
            new BookingResource($booking->load('field', 'timeSlot', 'payment')),
            'Booking retrieved successfully'
        );
    }

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        if ($booking->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        if ($booking->status !== 'pending') {
            return $this->errorResponse('Only pending bookings can be updated', 422);
        }

        if ($request->has('note')) {
            $booking->note = $request->note;
        }

        $booking->save();

        return $this->successResponse(
            new BookingResource($booking->load('field', 'timeSlot')),
            'Booking updated successfully'
        );
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return $this->errorResponse('Only pending or confirmed bookings can be cancelled', 422);
        }

        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->save();

        return $this->successResponse(
            new BookingResource($booking),
            'Booking cancelled successfully'
        );
    }
}
