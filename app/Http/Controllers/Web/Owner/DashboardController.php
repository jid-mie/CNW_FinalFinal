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

        $stats = [
            'total_fields' => $fieldIds->count(),
            'active_fields' => Field::where('owner_id', $userId)->where('status', 'active')->count(),
            'today_bookings' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('booking_date', today())->count(),
            'pending_bookings' => Booking::whereIn('field_id', $fieldIds)
                ->where('status', 'pending')->count(),
            'today_revenue' => (float) Booking::whereIn('field_id', $fieldIds)
                ->whereDate('booking_date', today())
                ->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
        ];

        $pendingBookings = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'pending')
            ->with(['customer', 'field', 'timeSlot'])
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'pendingBookings'));
    }
}
