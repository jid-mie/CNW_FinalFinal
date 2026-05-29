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

        // Lọc ngày theo lịch đặt thực tế hiển thị trên màn hình
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

        // 📊 TÍNH TOÁN SỐ LIỆU TỔNG CHO CÁC THẺ SỐ LIỆU
        $totalRevenue   = (clone $query)->whereIn('status', ['paid', 'success', 'completed'])->sum('amount');
        $unpaidAmount   = (clone $query)->whereIn('status', ['pending', 'unpaid', 'processing'])->sum('amount');
        $refundedAmount = (clone $query)->whereIn('status', ['refunded'])->sum('amount');

        // Phân trang kết quả (10 dòng/trang) và giữ lại tham số lọc trên URL
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

    /**
     * 📊 XỬ LÝ XUẤT DỮ LIỆU CSV: CHỈ XUẤT CÁC GIAO DỊCH ĐÃ THANH TOÁN (PAID)
     */
    public function exportCSV(Request $request)
    {
        $fileName = 'bao-cao-doanh-thu-paid-' . date('d-m-Y-His') . '.csv';

        // 1. Khởi tạo query kèm các bảng liên kết liên quan
        $query = Payment::with(['booking.customer', 'booking.field.sport']);

        // 🎯 THAY ĐỔI TẠI ĐÂY: Ép buộc hệ thống CHỈ LẤY các giao dịch có trạng thái là 'paid'
        $query->where('status', 'paid');
        
        // (Mẹo nhỏ: Nếu nhóm bạn quy ước nhiều từ khóa thành công như 'paid', 'success', 'completed', 
        // bạn có thể thay dòng trên thành: $query->whereIn('status', ['paid', 'success', 'completed']); nhé)

        // Vẫn giữ lại các bộ lọc khác nếu người dùng có chọn (Lọc ngày, phương thức, tìm kiếm...)
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

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('search')) {
            $query->where('transaction_code', 'LIKE', '%' . $request->search . '%');
        }

        // Lấy dữ liệu thỏa mãn điều kiện lọc
        $payments = $query->orderBy('id', 'desc')->get();

        // 2. Cấu hình Header tải file CSV định dạng UTF-8
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Mã Giao Dịch', 'Mã Lịch Đặt', 'Khách Hàng', 'Sân Đặt', 'Bộ Môn', 'Số Tiền Hóa Đơn', 'Phương Thức', 'Trạng Thái', 'Ngày Lịch Đặt'];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            
            // Chèn ký tự BOM để chống lỗi font tiếng Việt trong Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->transaction_code ?? 'TXN-' . $payment->id,
                    $payment->booking_id ? 'BK-' . $payment->booking_id : '---',
                    $payment->booking->customer->name ?? 'Khách lẻ',
                    $payment->booking->field->name ?? '---',
                    $payment->booking->field->sport->name ?? '---',
                    number_format($payment->amount) . 'đ',
                    strtoupper($payment->method),
                    strtoupper($payment->status),
                    $payment->booking->booking_date ? Carbon::parse($payment->booking->booking_date)->format('d/m/Y') : '---'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}