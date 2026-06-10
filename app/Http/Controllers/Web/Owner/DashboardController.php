<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\Booking;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $fieldIds = Field::where('owner_id', $userId)->pluck('id');

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $stats = [
            'total_fields' => $fieldIds->count(),
            'active_fields' => Field::where('owner_id', $userId)->where('status', 'active')->count(),
            'today_bookings' => Booking::whereIn('bookings.field_id', $fieldIds)
                ->whereDate('booking_date', today())->count(),
            'pending_bookings' => Booking::whereIn('bookings.field_id', $fieldIds)
                ->where('status', 'pending')->count(),
            'today_revenue' => (float) Booking::whereIn('bookings.field_id', $fieldIds)
                ->whereDate('booking_date', today())
                ->where(function ($query) {
                    $query->whereHas('payment', function ($q) {
                        $q->where('status', 'paid');
                    })->orWhere('status', 'completed');
                })
                ->sum('total_price'),
            'monthly_revenue' => (float) Booking::whereIn('bookings.field_id', $fieldIds)
                ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
                ->where(function ($query) {
                    $query->whereHas('payment', function ($q) {
                        $q->where('status', 'paid');
                    })->orWhere('status', 'completed');
                })
                ->sum('total_price'),
        ];

        $pendingBookings = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'pending')
            ->with(['customer', 'field', 'timeSlot', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::whereIn('bookings.field_id', $fieldIds)
            ->with(['customer', 'field', 'timeSlot'])
            ->join('time_slots', 'bookings.time_slot_id', '=', 'time_slots.id')
            ->orderBy('bookings.booking_date', 'desc')
            ->orderBy('time_slots.start_time', 'desc')
            ->select('bookings.*')
            ->take(5)
            ->get();

        $fields = Field::where('owner_id', $userId)
            ->with('sport')
            ->withCount(['timeSlots', 'bookings'])
            ->latest()
            ->take(3)
            ->get();

        return view('owner.dashboard', compact('stats', 'pendingBookings', 'recentBookings', 'fields'));
    }
}
