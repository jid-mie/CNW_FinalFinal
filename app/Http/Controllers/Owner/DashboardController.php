<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ownerId = auth()->id();

        // 1. Total fields owned by this owner
        $totalFields = Field::where('owner_id', $ownerId)->count();

        // 2. Bookings today
        $todayBookings = Booking::whereHas('field', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->whereDate('booking_date', today())->count();

        // 3. Pending bookings waiting for approval
        $pendingBookings = Booking::whereHas('field', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->where('status', 'pending')->count();

        // 4. Monthly revenue (paid bookings OR completed bookings)
        $monthlyRevenue = Booking::whereHas('field', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->where(function ($query) {
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 'paid');
                })->orWhere('status', 'completed');
            })
            ->sum('total_price');

        // 5. Recent Bookings (max 5, ordered by booking_date + time_slot start_time desc)
        $recentBookings = Booking::with(['customer', 'field', 'timeSlot', 'payment'])
            ->whereHas('field', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->join('time_slots', 'bookings.time_slot_id', '=', 'time_slots.id')
            ->select('bookings.*')
            ->orderBy('bookings.booking_date', 'desc')
            ->orderBy('time_slots.start_time', 'desc')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalFields',
            'todayBookings',
            'pendingBookings',
            'monthlyRevenue',
            'recentBookings'
        ));
    }

    public function confirm(int $id)
    {
        $ownerId = auth()->id();
        $booking = Booking::whereHas('field', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể duyệt các đặt lịch đang chờ xác nhận.');
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Đã duyệt đặt lịch thành công.');
    }

    public function cancel(int $id)
    {
        $ownerId = auth()->id();
        $booking = Booking::whereHas('field', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy các đặt lịch đang chờ xác nhận.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Đã hủy đặt lịch thành công.');
    }
}
