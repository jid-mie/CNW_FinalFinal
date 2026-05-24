<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\Field;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TimeSlotController extends Controller
{
    public function index(Field $field)
    {
        if ($field->owner_id !== auth()->id()) abort(403);
        $timeSlots = $field->timeSlots()->orderBy('start_time')->paginate(20);
        return view('owner.time-slots.index', compact('field', 'timeSlots'));
    }

    public function store(Request $request, Field $field)
    {
        if ($field->owner_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $overlap = $field->timeSlots()
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                  ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
            })->exists();

        if ($overlap) return back()->with('error', 'Khung giờ bị trùng lịch');

        $field->timeSlots()->create($data);

        return back()->with('success', 'Thêm khung giờ thành công');
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        if ($timeSlot->field->owner_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $timeSlot->update($data);

        return back()->with('success', 'Cập nhật khung giờ thành công');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        if ($timeSlot->field->owner_id !== auth()->id()) abort(403);
        $timeSlot->delete();
        return back()->with('success', 'Xoá khung giờ thành công');
    }

    public function generateDefault(Field $field)
    {
        if ($field->owner_id !== auth()->id()) abort(403);

        $existing = $field->timeSlots()->count();
        if ($existing > 0) return back()->with('error', 'Sân đã có khung giờ, không thể tạo mặc định');

        $times = [];
        for ($h = 6; $h < 22; $h++) {
            $times[] = [
                'field_id' => $field->id,
                'start_time' => sprintf('%02d:00', $h),
                'end_time' => sprintf('%02d:00', $h + 1),
                'price' => $field->price_per_hour,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        TimeSlot::insert($times);

        return back()->with('success', 'Đã tạo 16 khung giờ mặc định (06:00 - 22:00)');
    }
}
