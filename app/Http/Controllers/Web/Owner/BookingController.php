<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\Booking;
use App\Models\Field;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereHas('field', fn($q) => $q->where('owner_id', auth()->id()))
            ->with(['customer', 'field.sport', 'timeSlot', 'payment']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('field', fn($f) => $f->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('field_id')) $query->where('field_id', $request->field_id);
        if ($request->filled('date_from')) $query->whereDate('booking_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('booking_date', '<=', $request->date_to);

        $sortField = in_array($request->sort_field, ['booking_date','total_price','status','created_at']) ? $request->sort_field : 'created_at';
        $query->orderBy($sortField, $request->sort_dir === 'asc' ? 'asc' : 'desc');

        $bookings = $query->paginate(15)->withQueryString();
        $fields = Field::where('owner_id', auth()->id())->get();

        return view('owner.bookings.index', compact('bookings', 'fields'));
    }

    public function pending()
    {
        $bookings = Booking::whereHas('field', fn($q) => $q->where('owner_id', auth()->id()))
            ->where('status', 'pending')
            ->with(['customer', 'field', 'timeSlot'])
            ->latest()
            ->paginate(15);

        return view('owner.bookings.pending', compact('bookings'));
    }

    public function calendar(Request $request)
    {
        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);

        // Normalize year and month using Carbon to handle underflows (month < 1) and overflows (month > 12)
        $date = Carbon::createFromDate($year, $month, 1);
        $year = $date->year;
        $month = $date->month;
        $dateFrom = $date->copy()->startOfMonth()->toDateString();
        $dateTo = $date->copy()->endOfMonth()->toDateString();

        $bookings = Booking::whereHas('field', fn($q) => $q->where('owner_id', auth()->id()))
            ->whereBetween('booking_date', [$dateFrom, $dateTo])
            ->with(['customer', 'field', 'timeSlot', 'payment'])
            ->orderBy('booking_date')->orderBy('time_slot_id')
            ->get()
            ->groupBy(fn($b) => $b->booking_date->format('Y-m-d'));

        return view('owner.bookings.calendar', compact('bookings', 'year', 'month', 'dateFrom', 'dateTo'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) abort(403);
        if ($booking->status !== 'pending') return back()->with('error', 'Booking không ở trạng thái chờ duyệt');
        
        $booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);

        // Auto-cancel other overlapping pending bookings on the same field, date and time slot
        $cancelledCount = Booking::where('field_id', $booking->field_id)
            ->where('booking_date', $booking->booking_date)
            ->where('time_slot_id', $booking->time_slot_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'note' => 'Hệ thống tự động hủy do khung giờ đã được duyệt cho khách hàng khác.'
            ]);

        $message = $cancelledCount > 0 
            ? 'Đã duyệt đặt lịch và tự động hủy các lịch trùng.' 
            : 'Đã duyệt đặt lịch';

        return back()->with('success', $message);
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) abort(403);
        if (!in_array($booking->status, ['pending', 'confirmed'])) return back()->with('error', 'Không thể huỷ');
        $booking->update(['status' => 'cancelled', 'cancelled_at' => now(), 'note' => $request->note ?? $booking->note]);
        return back()->with('success', 'Đã huỷ đặt lịch');
    }

    public function checkin(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) abort(403);
        if ($booking->status !== 'confirmed') return back()->with('error', 'Chỉ check-in booking đã duyệt');
        if ($booking->booking_date->isFuture()) return back()->with('error', 'Không thể check-in trước ngày');
        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Check-in thành công');
    }
}
