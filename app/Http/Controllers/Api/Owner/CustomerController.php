<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ownerId = $request->user()->id;

        $customers = User::whereHas('bookings.field', fn ($q) => $q->where('owner_id', $ownerId))
            ->withCount(['bookings' => fn ($q) => $q->whereHas('field', fn ($f) => $f->where('owner_id', $ownerId))])
            ->withSum(['bookings as total_spent' => fn ($q) => $q->whereHas('field', fn ($f) => $f->where('owner_id', $ownerId))], 'total_price');

        // Search
        if ($search = $request->search) {
            $customers->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->sort_field ?? 'bookings_count';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'email', 'bookings_count', 'total_spent', 'created_at'];
        $sortField = in_array($sortField, $allowedSorts) ? $sortField : 'bookings_count';

        $customers->orderBy($sortField, $sortDir);

        $customers = $customers->paginate($request->per_page ?? 10);

        return $this->successResponse(
            CustomerResource::collection($customers)->response()->getData(true),
            'Danh sách khách hàng'
        );
    }

    public function show(Request $request, User $customer): JsonResponse
    {
        $ownerId = $request->user()->id;

        // Ensure customer has booked this owner's fields
        $hasBooked = $customer->bookings()->whereHas('field', fn ($q) => $q->where('owner_id', $ownerId))->exists();
        if (! $hasBooked) {
            return $this->errorResponse('Khách hàng không có lịch sử đặt sân của bạn', 404);
        }

        $customer->loadCount(['bookings' => fn ($q) => $q->whereHas('field', fn ($f) => $f->where('owner_id', $ownerId))]);

        $bookings = $customer->bookings()
            ->whereHas('field', fn ($q) => $q->where('owner_id', $ownerId))
            ->with(['field.sport', 'timeSlot', 'payment'])
            ->latest()
            ->paginate($request->per_page ?? 10);

        return $this->successResponse([
            'customer' => new CustomerResource($customer),
            'bookings' => BookingResource::collection($bookings)->response()->getData(true),
        ], 'Chi tiết khách hàng');
    }
}
