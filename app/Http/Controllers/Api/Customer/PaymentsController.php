<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentsController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $payments = Payment::query()
            ->whereHas('booking', function ($query) use ($request) {
                $query->where('customer_id', $request->user()->id);
            })
            ->with(['booking.field', 'booking.timeSlot'])
            ->latest()
            ->paginate(10);

        return $this->successResponse(
            PaymentResource::collection($payments),
            'Payments retrieved successfully'
        );
    }

    public function show(Request $request, Payment $payment): JsonResponse
    {
        $payment->load(['booking.field', 'booking.timeSlot']);

        if ($payment->booking->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        return $this->successResponse(
            new PaymentResource($payment),
            'Payment retrieved successfully'
        );
    }

    public function store(StorePaymentRequest $request, Booking $booking): JsonResponse
    {
        if ($booking->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        if ($booking->status === 'cancelled') {
            return $this->errorResponse('Cancelled booking cannot be paid', 422);
        }

        if ($booking->payment()->exists()) {
            return $this->errorResponse('Payment already exists for this booking', 409);
        }

        $payment = DB::transaction(function () use ($booking, $request) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'method' => $request->input('method', 'cash'),
                'status' => 'paid',
                'transaction_code' => 'PMT-'.Str::upper(Str::random(10)),
                'paid_at' => now(),
                'note' => $request->note,
            ]);

            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return $payment->load('booking.field', 'booking.timeSlot');
        });

        return $this->successResponse(
            new PaymentResource($payment),
            'Payment created successfully',
            201
        );
    }
}
