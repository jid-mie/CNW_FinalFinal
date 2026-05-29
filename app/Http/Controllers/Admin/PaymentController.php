<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn thanh toán kèm bộ lọc khoảng thời gian chuẩn xác
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo Query kết nối các bảng liên quan tránh lỗi N+1 query
        $query = Payment::with(['booking.customer', 'booking.field.sport']);

        // 🎯 SỬA LỖI LỌC NGÀY: Lọc độc lập và nhắm thẳng vào ngày lịch đặt thực tế hiển thị trên màn hình
        if ($request->filled('start_date')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->whereDate('booking_date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->whereDate('booking_date', '<=', $request->end_date);
            });
        }

        // Bộ lọc theo phương thức thanh toán (momo, vnpay, cash, bank_transfer)
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        // Bộ lọc theo trạng thái kiểm toán tiền (paid, pending, refunded)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Bộ lọc tra cứu nhanh theo mã giao dịch
        if ($request->filled('search')) {
            $query->where('transaction_code', 'LIKE', '%' . $request->search . '%');
        }

        // 📊 2. TÍNH TOÁN SỐ LIỆU TỔNG CHO 4 THẺ SỐ LIỆU (Biến động động theo bộ lọc)
        $totalRevenue   = (clone $query)->whereIn('status', ['paid', 'success', 'completed'])->sum('amount');
        $unpaidAmount   = (clone $query)->whereIn('status', ['pending', 'unpaid', 'processing'])->sum('amount');
        $refundedAmount = (clone $query)->whereIn('status', ['refunded'])->sum('amount');

        // 3. Phân trang kết quả (10 dòng/trang) và giữ lại tham số lọc trên URL
        $payments = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.payments.index', compact('payments', 'totalRevenue', 'unpaidAmount', 'refundedAmount'));
    }

    /**
     * Hiển thị Form xem chi tiết từng chứng từ hóa đơn cụ thể
     */
    public function show($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.field.sport'])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}