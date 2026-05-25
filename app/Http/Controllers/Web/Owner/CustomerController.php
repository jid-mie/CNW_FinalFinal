<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = auth()->id();

        $customers = User::whereHas('bookings.field', fn($q) => $q->where('owner_id', $ownerId))
            ->withCount(['bookings' => fn($q) => $q->whereHas('field', fn($f) => $f->where('owner_id', $ownerId))])
            ->withSum(['bookings as total_spent' => fn($q) => $q->whereHas('field', fn($f) => $f->where('owner_id', $ownerId))], 'total_price');

        if ($search = $request->search) {
            $customers->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sortField = in_array($request->sort_field, ['name', 'bookings_count', 'total_spent', 'created_at']) ? $request->sort_field : 'bookings_count';
        $customers->orderBy($sortField, $request->sort_dir === 'asc' ? 'asc' : 'desc');

        $customers = $customers->paginate(15)->withQueryString();

        return view('owner.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $ownerId = auth()->id();
        $hasBooked = $customer->bookings()->whereHas('field', fn($q) => $q->where('owner_id', $ownerId))->exists();
        if (!$hasBooked) abort(404);

        $bookings = $customer->bookings()
            ->whereHas('field', fn($q) => $q->where('owner_id', $ownerId))
            ->with(['field.sport', 'timeSlot', 'payment'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_bookings' => $customer->bookings()->whereHas('field', fn($q) => $q->where('owner_id', $ownerId))->count(),
            'total_spent' => (float) $customer->bookings()->whereHas('field', fn($q) => $q->where('owner_id', $ownerId))->sum('total_price'),
            'last_booking' => $customer->bookings()->whereHas('field', fn($q) => $q->where('owner_id', $ownerId))->latest()->first()?->booking_date,
        ];

        return view('owner.customers.show', compact('customer', 'bookings', 'stats'));
    }
}
