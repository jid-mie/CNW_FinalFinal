<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $customerId = $request->user()->id;

        // Upcoming bookings: status pending or confirmed, and date is today or later
        $upcomingBookingsCount = Booking::where('customer_id', $customerId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->count();

        // Completed bookings count: status completed or confirmed with past date
        $completedBookingsCount = Booking::where('customer_id', $customerId)
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere(function ($q) {
                        $q->where('status', 'confirmed')
                            ->whereDate('booking_date', '<', now()->toDateString());
                    });
            })
            ->count();

        // Latest bookings list
        $bookings = Booking::where('customer_id', $customerId)
            ->with(['field.sport', 'timeSlot', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('time_slot_id', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'upcomingBookingsCount',
            'completedBookingsCount',
            'bookings'
        ));
    }

    public function cancelBooking(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch đặt ở trạng thái chờ duyệt hoặc đã xác nhận.');
        }

        $booking->status = 'cancelled';
        $booking->cancelled_at = \Illuminate\Support\Carbon::now();
        $booking->save();

        return back()->with('success', 'Hủy đặt sân thành công.');
    }

    public function getBookingStatus(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tải thông tin thanh toán nếu có
        $booking->load('payment');

        return response()->json([
            'id' => $booking->id,
            'status' => $booking->status,
            'is_paid' => $booking->payment && $booking->payment->status === 'paid',
            'payment_method' => $booking->payment ? $booking->payment->method : null,
        ]);
    }
}
