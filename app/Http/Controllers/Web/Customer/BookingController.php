<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\Sport;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('customer_id', $request->user()->id)
            ->with(['field.sport', 'timeSlot', 'payment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $sports = Sport::where('is_active', true)->get();

        return view('customer.bookings.create', compact('sports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'note' => 'nullable|string|max:500',
        ]);

        $field = Field::findOrFail($request->field_id);
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);

        if ($field->status !== 'active') {
            return back()->with('error', 'Sân không hoạt động')->withInput();
        }

        if (! $timeSlot->is_active) {
            return back()->with('error', 'Khung giờ không khả dụng')->withInput();
        }

        if ($timeSlot->field_id !== $field->id) {
            return back()->with('error', 'Khung giờ không thuộc sân này')->withInput();
        }

        $exists = Booking::where('field_id', $field->id)
            ->where('time_slot_id', $timeSlot->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Khung giờ này đã có người đặt')->withInput();
        }

        $booking = Booking::create([
            'customer_id' => $request->user()->id,
            'field_id' => $field->id,
            'time_slot_id' => $timeSlot->id,
            'booking_date' => $request->booking_date,
            'total_price' => $field->price_per_hour,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Đặt sân thành công! Vui lòng thanh toán để xác nhận.');
    }

    /**
     * API-like endpoint: get fields by sport (for AJAX)
     */
    public function getFieldsBySport(Sport $sport)
    {
        $fields = Field::where('sport_id', $sport->id)
            ->where('status', 'active')
            ->get(['id', 'name', 'address', 'price_per_hour', 'code', 'description', 'image_url']);

        return response()->json($fields);
    }

    /**
     * API-like endpoint: get available time slots for a field on a date (for AJAX)
     */
    public function getAvailableSlots(Request $request, Field $field)
    {
        $request->validate(['date' => 'required|date']);

        $date = $request->date;
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

        $bookedSlotIds = Booking::where('field_id', $field->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('time_slot_id');

        $slots = TimeSlot::where('field_id', $field->id)
            ->where('is_active', true)
            ->get()
            ->map(function ($slot) use ($bookedSlotIds) {
                $slot->is_available = ! $bookedSlotIds->contains($slot->id);

                return $slot;
            });

        return response()->json([
            'field' => ['id' => $field->id, 'price_per_hour' => $field->price_per_hour],
            'slots' => $slots,
        ]);
    }
}
