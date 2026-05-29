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
            ->where('status', 'paid')
            ->sum('amount');

        $recentUsers = User::with('role')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalCustomers',
            'pendingBookings',
            'totalRevenue',
            'recentUsers',
        ));
    }
}