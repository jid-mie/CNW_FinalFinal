@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen flex flex-col items-center justify-start pt-12">
    
    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden animate-in fade-in duration-200">
        
        <div class="bg-[#111625] px-6 py-5 text-white flex justify-between items-center">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-[#52ffa2]">Chi tiết chứng từ</h3>
                <h1 class="text-base font-bold text-slate-200 mt-0.5">Hóa đơn giao dịch hệ thống</h1>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center space-x-1">
                <span>↩</span> <span>Quay lại danh sách</span>
            </a>
        </div>

        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mã giao dịch (Hệ thống)</label>
                    <input type="text" value="{{ $payment->transaction_code ?? 'TXN-'.str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}" readonly class="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-mono font-bold text-slate-800 shadow-sm focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mã lịch đặt liên kết</label>
                    <input type="text" value="BK-{{ str_pad($payment->booking->id ?? 0, 3, '0', STR_PAD_LEFT) }}" readonly class="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-mono font-bold text-slate-400 shadow-sm focus:outline-none">
                </div>
            </div>

            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Thông tin khách hàng đặt sân</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-[11px] text-slate-400 font-semibold">Tên khách hàng:</p>
                        <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $payment->booking->customer->name ?? 'Khách vãng lai' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 font-semibold">Số điện thoại:</p>
                        <p class="text-xs font-mono font-bold text-slate-600 mt-0.5">{{ $payment->booking->customer->phone ?? '---' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cơ sở vật chất đặt thuê</label>
                    <input type="text" value="{{ $payment->booking->field->name ?? 'N/A' }} @if(isset($payment->booking->field->sport)) ({{ $payment->booking->field->sport->name }}) @endif" readonly class="w-full p-3 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ngày sử dụng sân</label>
                    <input type="text" value="{{ $payment->booking->booking_date ? \Carbon\Carbon::parse($payment->booking->booking_date)->format('d/m/Y') : '---' }}" readonly class="w-full p-3 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tổng giá trị hóa đơn</label>
                    <div class="relative">
                        <input type="text" value="{{ number_format($payment->amount) }}đ" readonly class="w-full p-3 border border-slate-200 rounded-xl text-sm font-black text-slate-800 font-mono focus:outline-none bg-slate-50/30">
                        <span class="absolute right-4 top-3 text-xs">💰</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hình thức thanh toán</label>
                    <input type="text" value="🏦 Chuyển khoản ngân hàng" readonly class="w-full p-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái duyệt tiền</label>
                    <div class="w-full">
                        @if(in_array(strtolower($payment->status ?? ''), ['success', 'paid', 'completed']))
                            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-4 py-2.5 rounded-xl border border-emerald-100 block text-center tracking-wider uppercase">Đã thanh toán thành công</span>
                        @elseif(in_array(strtolower($payment->status ?? ''), ['pending', 'unpaid', 'processing']))
                            <span class="bg-[#eff6ff] text-[#3b82f6] text-[10px] font-black px-4 py-2.5 rounded-xl border border-blue-100 block text-center tracking-wider uppercase">Đang chờ xử lý lệnh</span>
                        @else
                            <span class="bg-[#fef2f2] text-[#ef4444] text-[10px] font-black px-4 py-2.5 rounded-xl border border-red-100 block text-center tracking-wider uppercase">Đã hoàn trả dòng tiền</span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Thời gian thanh toán</label>
                    <input type="text" value="{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y - H:i:s') : 'Chưa ghi nhận dòng tiền' }}" readonly class="w-full p-3 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ghi chú giao dịch nội bộ</label>
                <textarea readonly rows="2" class="w-full p-3 border border-slate-200 rounded-xl text-xs font-medium text-slate-500 focus:outline-none bg-slate-50/20 resize-none">{{ $payment->note ?? 'Không có ghi chú bổ sung từ hệ thống.' }}</textarea>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <a href="{{ route('admin.payments.index') }}" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-6 py-3 rounded-xl transition shadow-md tracking-wider uppercase">
                    Đóng cửa sổ xem
                </a>
            </div>
        </div>
    </div>
</div>
@endsection