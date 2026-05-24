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

        $query = Booking::whereIn('field_id', $fieldIds)->whereIn('bookings.status', ['confirmed', 'completed']);

        $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        $query->whereDate('booking_date', '>=', $startDate)->whereDate('booking_date', '<=', $endDate);

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

        $fields = Field::where('owner_id', $userId)->get();

        return view('owner.revenue.index', compact(
            'totalRevenue', 'totalBookings', 'daily', 'byMethod', 'byField', 'startDate', 'endDate', 'fields'
        ));
    }
}
