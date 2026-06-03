<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Owner\StoreTimeSlotRequest;
use App\Http\Resources\TimeSlotResource;
use App\Models\Field;
use App\Models\TimeSlot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xem khung giờ của sân này', 403);
        }

        $slots = $field->timeSlots()->orderBy('start_time')->get();

        return $this->successResponse(
            TimeSlotResource::collection($slots),
            'Danh sách khung giờ'
        );
    }

    public function store(StoreTimeSlotRequest $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền thêm khung giờ cho sân này', 403);
        }

        // Check overlap
        $overlap = $field->timeSlots()
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->exists();

        if ($overlap) {
            return $this->errorResponse('Khung giờ bị trùng với khung giờ đã tồn tại', 422);
        }

        $slot = $field->timeSlots()->create($request->validated());

        return $this->successResponse(
            new TimeSlotResource($slot),
            'Thêm khung giờ thành công',
            201
        );
    }

    public function update(StoreTimeSlotRequest $request, Field $field, TimeSlot $timeSlot): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền sửa khung giờ này', 403);
        }

        if ($timeSlot->field_id !== $field->id) {
            return $this->errorResponse('Khung giờ không thuộc sân này', 400);
        }

        // Check overlap excluding self
        $overlap = $field->timeSlots()
            ->where('id', '!=', $timeSlot->id)
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->exists();

        if ($overlap) {
            return $this->errorResponse('Khung giờ bị trùng với khung giờ đã tồn tại', 422);
        }

        $timeSlot->update($request->validated());

        return $this->successResponse(
            new TimeSlotResource($timeSlot->fresh()),
            'Cập nhật khung giờ thành công'
        );
    }

    public function destroy(Request $request, Field $field, TimeSlot $timeSlot): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xoá khung giờ này', 403);
        }

        if ($timeSlot->field_id !== $field->id) {
            return $this->errorResponse('Khung giờ không thuộc sân này', 400);
        }

        $timeSlot->delete();

        return $this->successResponse(null, 'Xoá khung giờ thành công');
    }

    public function generateDefault(Request $request, Field $field): JsonResponse
    {
        if ($field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền thao tác trên sân này', 403);
        }

        // Generate default 1-hour slots from 06:00 to 22:00
        $start = 6;
        $end = 22;
        $created = [];

        for ($h = $start; $h < $end; $h++) {
            $startTime = sprintf('%02d:00', $h);
            $endTime = sprintf('%02d:00', $h + 1);

            $exists = $field->timeSlots()
                ->where('start_time', $startTime)
                ->where('end_time', $endTime)
                ->exists();

            if (!$exists) {
                $slot = $field->timeSlots()->create([
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_active' => true,
                ]);
                $created[] = $slot;
            }
        }

        return $this->successResponse(
            TimeSlotResource::collection(collect($created)),
            'Đã tạo ' . count($created) . ' khung giờ mặc định (06:00 - 22:00)'
        );
    }
}
