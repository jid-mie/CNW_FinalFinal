<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
   public function index()
    {
        $payments = Payment::with('booking.field')->orderBy('created_at', 'desc')->get();

        // Kiểm tra tập hợp các từ khóa tương đương đề phòng Git nhóm dùng từ khác nhau
        $totalRevenue = Payment::whereIn('status', ['success', 'paid', 'completed', 'Success', 'Paid'])->sum('amount');
        $unpaidAmount = Payment::whereIn('status', ['pending', 'unpaid', 'processing', 'Pending', 'Unpaid'])->sum('amount');
        $refundedAmount = Payment::whereIn('status', ['refunded', 'failed', 'cancelled', 'Refunded', 'Failed'])->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalRevenue', 'unpaidAmount', 'refundedAmount'));
    }
}