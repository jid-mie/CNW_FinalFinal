<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Owner\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::whereHas('field', fn ($q) => $q->where('owner_id', $request->user()->id))
            ->with(['customer', 'field.sport', 'timeSlot', 'payment']);

        // Search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"))
                    ->orWhereHas('field', fn ($f) => $f->where('name', 'like', "%{$search}%"))
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        // Sort
        $sortField = $request->sort_field ?? 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['booking_date', 'total_price', 'status', 'created_at', 'updated_at'];
        $sortField = in_array($sortField, $allowedSorts) ? $sortField : 'created_at';

        $query->orderBy($sortField, $sortDir);

        $bookings = $query->paginate($request->per_page ?? 10);

        return $this->successResponse(
            BookingResource::collection($bookings)->response()->getData(true),
            'Danh sách đặt lịch'
        );
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xem đặt lịch này', 403);
        }

        $booking->load(['customer', 'field.sport', 'timeSlot', 'payment']);

        return $this->successResponse(
            new BookingResource($booking),
            'Chi tiết đặt lịch'
        );
    }

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        if ($booking->field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền xử lý đặt lịch này', 403);
        }

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return $this->errorResponse(
                'Không thể thay đổi trạng thái hiện tại ('.$booking->status.')',
                422
            );
        }

        $status = $request->status;

        if ($status === 'confirmed' && (! $booking->payment || $booking->payment->status !== 'paid')) {
            return $this->errorResponse('Không thể duyệt khi chưa thanh toán thành công!', 422);
        }

        $booking->update([
            'status' => $status,
            'note' => $request->note ?? $booking->note,
            'confirmed_at' => $status === 'confirmed' ? now() : $booking->confirmed_at,
            'cancelled_at' => $status === 'cancelled' ? now() : $booking->cancelled_at,
        ]);

        $message = $status === 'confirmed'
            ? 'Đã duyệt đặt lịch thành công'
            : 'Đã từ chối đặt lịch';

        return $this->successResponse(
            new BookingResource($booking->load(['customer', 'field.sport', 'timeSlot'])),
            $message
        );
    }

    /**
     * Check-in: mark confirmed booking as completed
     */
    public function checkin(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->field->owner_id !== $request->user()->id) {
            return $this->errorResponse('Bạn không có quyền thao tác đặt lịch này', 403);
        }

        if ($booking->status !== 'confirmed') {
            return $this->errorResponse(
                'Chỉ có thể check-in đặt lịch đã được duyệt (trạng thái hiện tại: '.$booking->status.')',
                422
            );
        }

        if ($booking->booking_date->isFuture()) {
            return $this->errorResponse('Không thể check-in trước ngày đặt lịch', 422);
        }

        $booking->update([
            'status' => 'completed',
            'note' => $request->note ?? $booking->note,
        ]);

        return $this->successResponse(
            new BookingResource($booking->load(['customer', 'field.sport', 'timeSlot'])),
            'Check-in thành công'
        );
    }

    public function pending(Request $request): JsonResponse
    {
        $query = Booking::whereHas('field', fn ($q) => $q->where('owner_id', $request->user()->id))
            ->where('status', 'pending')
            ->with(['customer', 'field.sport', 'timeSlot']);

        // Search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"))
                    ->orWhereHas('field', fn ($f) => $f->where('name', 'like', "%{$search}%"));
            });
        }

        // Sort
        $sortField = $request->sort_field ?? 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['booking_date', 'total_price', 'created_at'];
        $sortField = in_array($sortField, $allowedSorts) ? $sortField : 'created_at';

        $query->orderBy($sortField, $sortDir);

        $bookings = $query->paginate($request->per_page ?? 10);

        return $this->successResponse(
            BookingResource::collection($bookings)->response()->getData(true),
            'Danh sách đặt lịch chờ duyệt'
        );
    }

    public function stats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $fieldIds = Field::where('owner_id', $userId)->pluck('id');

        $stats = [
            'total_fields' => $fieldIds->count(),
            'active_fields' => Field::where('owner_id', $userId)->where('status', 'active')->count(),
            'today_bookings' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('booking_date', today())
                ->count(),
            'pending_bookings' => Booking::whereIn('field_id', $fieldIds)
                ->where('status', 'pending')
                ->count(),
            'today_revenue' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('booking_date', today())
                ->where('status', 'confirmed')
                ->sum('total_price'),
        ];

        return $this->successResponse($stats, 'Thống kê tổng quan');
    }

    /**
     * Revenue report with date range, group by period/method/sport
     */
    public function revenue(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $fieldIds = Field::where('owner_id', $userId)->pluck('id');

        if ($fieldIds->isEmpty()) {
            return $this->successResponse([
                'total_revenue' => 0,
                'total_bookings' => 0,
                'breakdown' => [],
                'summary' => [],
            ], 'Báo cáo doanh thu');
        }

        $query = Booking::whereIn('field_id', $fieldIds)
            ->whereIn('status', ['confirmed', 'completed']);

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        // Default: last 30 days
        if (! $request->filled('start_date') && ! $request->filled('end_date')) {
            $query->whereDate('booking_date', '>=', now()->subDays(30));
        }

        // Total
        $totalRevenue = (float) $query->sum('total_price');
        $totalBookings = $query->count();

        // Breakdown by booking date (daily)
        $daily = (clone $query)
            ->selectRaw('booking_date, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('booking_date')
            ->orderBy('booking_date')
            ->get()
            ->map(fn ($b) => [
                'date' => $b->booking_date->format('Y-m-d'),
                'bookings' => $b->count,
                'revenue' => (float) $b->revenue,
            ]);

        // Breakdown by payment method (cần join payments)
        $byMethod = (clone $query)
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->selectRaw('payments.method, COUNT(*) as count, SUM(bookings.total_price) as revenue')
            ->groupBy('payments.method')
            ->get()
            ->map(fn ($b) => [
                'method' => $b->method,
                'bookings' => $b->count,
                'revenue' => (float) $b->revenue,
            ]);

        // Breakdown by field
        $byField = (clone $query)
            ->selectRaw('field_id, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('field_id')
            ->with('field:id,name')
            ->get()
            ->map(fn ($b) => [
                'field_id' => $b->field_id,
                'field_name' => $b->field->name ?? 'N/A',
                'bookings' => $b->count,
                'revenue' => (float) $b->revenue,
            ]);

        // Summary stats
        $summary = [
            'total_revenue' => $totalRevenue,
            'total_bookings' => $totalBookings,
            'avg_revenue_per_booking' => $totalBookings > 0 ? round($totalRevenue / $totalBookings, 2) : 0,
            'period_start' => $request->start_date ?? now()->subDays(30)->format('Y-m-d'),
            'period_end' => $request->end_date ?? now()->format('Y-m-d'),
        ];

        return $this->successResponse([
            'summary' => $summary,
            'by_date' => $daily,
            'by_payment_method' => $byMethod,
            'by_field' => $byField,
        ], 'Báo cáo doanh thu');
    }

    /**
     * Export bookings as CSV.
     */
    public function exportBookings(Request $request)
    {
        $query = Booking::whereHas('field', fn ($q) => $q->where('owner_id', $request->user()->id))
            ->with(['customer', 'field.sport', 'timeSlot', 'payment']);

        // Apply same filters as index
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"))
                    ->orWhereHas('field', fn ($f) => $f->where('name', 'like', "%{$search}%"))
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }
        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }

        $query->orderBy('booking_date', 'desc');

        $bookings = $query->get();
        $filename = 'bookings_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Khách hàng', 'SĐT', 'Sân', 'Ngày đặt', 'Giờ', 'Giá', 'Trạng thái', 'Thanh toán', 'Ghi chú', 'Ngày tạo']);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->id,
                    $b->customer?->name ?? 'N/A',
                    $b->customer?->phone ?? '',
                    $b->field?->name ?? 'N/A',
                    $b->booking_date?->format('d/m/Y'),
                    ($b->timeSlot?->start_time?->format('H:i') ?? '').' - '.($b->timeSlot?->end_time?->format('H:i') ?? ''),
                    number_format($b->total_price, 0, ',', '.'),
                    $b->status,
                    $b->payment?->status ?? 'N/A',
                    $b->note ?? '',
                    $b->created_at?->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export revenue report as CSV.
     */
    public function exportRevenue(Request $request)
    {
        $userId = $request->user()->id;
        $fieldIds = Field::where('owner_id', $userId)->pluck('id');

        $query = Booking::whereIn('field_id', $fieldIds)
            ->whereIn('status', ['confirmed', 'completed']);

        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }
        if (! $request->filled('start_date') && ! $request->filled('end_date')) {
            $query->whereDate('booking_date', '>=', now()->subDays(30));
        }

        $daily = (clone $query)
            ->selectRaw('booking_date, COUNT(*) as count, SUM(total_price) as revenue')
            ->with('field:id,name')
            ->groupBy('booking_date')
            ->orderBy('booking_date')
            ->get();

        $filename = 'revenue_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($daily) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Ngày', 'Số lượng booking', 'Doanh thu']);

            foreach ($daily as $d) {
                fputcsv($file, [
                    $d->booking_date?->format('d/m/Y'),
                    $d->count,
                    number_format($d->revenue, 0, ',', '.'),
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Calendar view: bookings grouped by date.
     * GET /api/owner/bookings/calendar?year=2026&month=5
     * GET /api/owner/bookings/calendar?date_from=2026-05-01&date_to=2026-05-31
     */
    public function calendar(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Determine date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
        } else {
            $year = $request->integer('year', now()->year);
            $month = $request->integer('month', now()->month);
            $dateFrom = sprintf('%04d-%02d-01', $year, $month);
            $dateTo = date('Y-m-t', strtotime($dateFrom));
        }

        $bookings = Booking::whereHas('field', fn ($q) => $q->where('owner_id', $userId))
            ->whereBetween('booking_date', [$dateFrom, $dateTo])
            ->with(['customer:id,name,phone', 'field:id,name,code', 'timeSlot:id,start_time,end_time', 'payment:id,booking_id,status,method'])
            ->orderBy('booking_date')
            ->orderBy('time_slot_id')
            ->get();

        // Group by date
        $byDate = $bookings->groupBy(fn ($b) => $b->booking_date->format('Y-m-d'))
            ->map(fn ($items, $date) => [
                'date' => $date,
                'day_of_week' => $items->first()->booking_date->dayName,
                'total' => $items->count(),
                'bookings' => $items->map(fn ($b) => [
                    'id' => $b->id,
                    'field_id' => $b->field_id,
                    'field_name' => $b->field?->name,
                    'customer_name' => $b->customer?->name ?? 'N/A',
                    'customer_phone' => $b->customer?->phone ?? '',
                    'start_time' => $b->timeSlot?->start_time?->format('H:i'),
                    'end_time' => $b->timeSlot?->end_time?->format('H:i'),
                    'total_price' => (float) $b->total_price,
                    'status' => $b->status,
                    'payment_status' => $b->payment?->status ?? null,
                    'payment_method' => $b->payment?->method ?? null,
                ]),
            ])->values();

        // Summary
        $summary = [
            'total_bookings' => $bookings->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
            'revenue' => (float) $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
        ];

        return $this->successResponse([
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'summary' => $summary,
            'by_date' => $byDate,
        ], 'Lịch đặt sân');
    }
}
