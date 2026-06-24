<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalOwners = User::whereHas('role', function ($query) {
            $query->where('name', 'owner');
        })->count();
        $totalCustomers = User::whereHas('role', function ($query) {
            $query->where('name', 'customer');
        })->count();
        $pendingBookings = DB::table('bookings')
            ->where('status', 'pending')
            ->count();
        $totalRevenue = (float) DB::table('payments')
            ->whereIn('status', ['paid', 'success', 'completed'])
            ->sum('amount');

        $recentUsers = User::with('role')
            ->latest()
            ->limit(5)
            ->get();

        // 📈 Doanh thu 6 tháng gần nhất (phương pháp an toàn độc lập hệ quản trị cơ sở dữ liệu)
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlySum = (float) DB::table('payments')
                ->whereIn('status', ['paid', 'success', 'completed'])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $revenueLabels[] = $month->format('m/Y');
            $revenueData[] = $monthlySum;
        }

        // ⚽ Thống kê số lượng đặt sân theo từng môn thể thao (loại trừ các môn và sân đã xóa tạm)
        $sportsBookings = DB::table('bookings')
            ->join('fields', 'bookings.field_id', '=', 'fields.id')
            ->join('sports', 'fields.sport_id', '=', 'sports.id')
            ->whereNull('sports.deleted_at')
            ->whereNull('fields.deleted_at')
            ->select('sports.name', DB::raw('count(bookings.id) as count'))
            ->groupBy('sports.name')
            ->get();

        $sportLabels = $sportsBookings->pluck('name')->toArray();
        $sportData = $sportsBookings->pluck('count')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalCustomers',
            'pendingBookings',
            'totalRevenue',
            'recentUsers',
            'revenueLabels',
            'revenueData',
            'sportLabels',
            'sportData'
        ));
    }
}
