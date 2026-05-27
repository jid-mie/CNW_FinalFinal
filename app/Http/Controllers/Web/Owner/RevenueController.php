<?php

namespace App\Http\Controllers\Web\Owner;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $fieldIds = Field::where('owner_id', $userId)->pluck('id');

        $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $query = Booking::whereIn('field_id', $fieldIds)
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereHas('payment', function ($p) {
                    $p->where('status', 'paid');
                })->orWhere('bookings.status', 'completed');
            });

        $totalRevenue = (float) $query->sum('total_price');
        $totalBookings = $query->count();

        $daily = (clone $query)
            ->selectRaw('booking_date, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('booking_date')->orderBy('booking_date')
            ->get();

        $byMethod = (clone $query)
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->selectRaw('payments.method, COUNT(*) as count, SUM(bookings.total_price) as revenue')
            ->groupBy('payments.method')->get();

        $byField = (clone $query)
            ->selectRaw('field_id, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('field_id')->with('field:id,name')
            ->get()
            ->map(fn($b) => ['field_name' => $b->field->name ?? 'N/A', 'bookings' => $b->count, 'revenue' => (float) $b->revenue]);

        if ($request->export) {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="revenue_report_' . $startDate . '_' . $endDate . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($daily, $byMethod, $byField, $totalRevenue, $totalBookings, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                // Add UTF-8 BOM for Excel support
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, ['BÁO CÁO DOANH THU (' . $startDate . ' đến ' . $endDate . ')']);
                fputcsv($file, ['Tổng doanh thu', number_format($totalRevenue, 0, '', '') . ' VNĐ']);
                fputcsv($file, ['Tổng đặt lịch', $totalBookings]);
                fputcsv($file, []);

                fputcsv($file, ['THỐNG KÊ THEO NGÀY']);
                fputcsv($file, ['Ngày', 'Số lượng đặt lịch', 'Doanh thu']);
                foreach ($daily as $d) {
                    fputcsv($file, [$d->booking_date->format('d/m/Y'), $d->count, $d->revenue]);
                }
                fputcsv($file, []);

                fputcsv($file, ['THỐNG KÊ THEO PHƯƠNG THỨC THANH TOÁN']);
                fputcsv($file, ['Phương thức', 'Số lượng đặt lịch', 'Doanh thu']);
                foreach ($byMethod as $m) {
                    fputcsv($file, [ucfirst($m->method), $m->count, $m->revenue]);
                }
                fputcsv($file, []);

                fputcsv($file, ['THỐNG KÊ THEO SÂN']);
                fputcsv($file, ['Tên sân', 'Số lượng đặt lịch', 'Doanh thu']);
                foreach ($byField as $f) {
                    fputcsv($file, [$f['field_name'], $f['bookings'], $f['revenue']]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $fields = Field::where('owner_id', $userId)->get();

        return view('owner.revenue.index', compact(
            'totalRevenue', 'totalBookings', 'daily', 'byMethod', 'byField', 'startDate', 'endDate', 'fields'
        ));
    }
}
