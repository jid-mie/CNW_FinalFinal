@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">
    
    <div class="flex justify-between items-center mb-6">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> <span class="text-slate-600 font-semibold underline cursor-pointer">Quản lý thanh toán</span>
        </div>
        <div class="flex items-center space-x-4 text-slate-400 text-sm">
            <span class="cursor-pointer hover:text-slate-700">🔔</span> 
            <span class="cursor-pointer hover:text-slate-700">⚙️</span>
            <div class="flex items-center space-x-2 border-l pl-4 border-slate-200">
                <div class="w-7 h-7 bg-slate-800 text-[#52ffa2] font-black rounded-full flex items-center justify-center text-[10px]">AD</div>
                <div class="text-[11px]">
                   
                    <p class="text-[9px] text-slate-400 mt-0.5 uppercase font-bold tracking-wider">Hệ thống Admin</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight uppercase">Quản lý thanh toán hệ thống</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">Theo dõi lịch sử dòng tiền phát sinh từ hệ thống đặt lịch.</p>
        </div>
        <a href="{{ route('admin.payments.export-csv', request()->all()) }}" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl flex items-center space-x-2 transition shadow-sm tracking-wider uppercase inline-flex items-center">
            <span>📊</span> <span>Xuất báo cáo (CSV)</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-[#111625] p-4 rounded-2xl text-white shadow-md relative overflow-hidden flex flex-col justify-between h-24">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Tổng dòng tiền thực tế</span>
            <h3 class="text-xl font-black mt-1 text-[#52ffa2] font-mono">{{ number_format($totalRevenue + $unpaidAmount) }}đ</h3>
            <span class="text-[9px] text-slate-400 block font-medium">📈 Dữ liệu thời gian thực</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-24">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex justify-between items-center">Đã thanh toán <span class="text-emerald-500 text-xs">🟢</span></span>
            <h3 class="text-xl font-black text-slate-800 font-mono">{{ number_format($totalRevenue) }}đ</h3>
            <div class="w-full bg-slate-100 h-1 rounded-full"><div class="bg-emerald-500 h-1 rounded-full" style="width: 100%"></div></div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-24">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex justify-between items-center">Chờ giao dịch <span class="text-blue-500 text-xs">🔵</span></span>
            <h3 class="text-xl font-black text-slate-800 font-mono">{{ number_format($unpaidAmount) }}đ</h3>
            <div class="w-full bg-slate-100 h-1 rounded-full"><div class="bg-blue-500 h-1 rounded-full" style="width: 100%"></div></div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-24">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider flex justify-between items-center">Đã hoàn trả <span class="text-red-500 text-xs">🔴</span></span>
            <h3 class="text-xl font-black text-slate-800 font-mono">{{ number_format($refundedAmount) }}đ</h3>
            <div class="w-full bg-slate-100 h-1 rounded-full"><div class="bg-red-500 h-1 rounded-full" style="width: 100%"></div></div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.payments.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Khoảng thời gian</label>
            <div class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full p-2 border border-slate-200 rounded-xl text-xs focus:outline-none text-slate-500 font-medium">
                <span class="text-slate-300 text-xs font-bold">~</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full p-2 border border-slate-200 rounded-xl text-xs focus:outline-none text-slate-500 font-medium">
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Phương thức ví</label>
            <select name="method" class="w-full p-2 border border-slate-200 rounded-xl text-xs bg-white text-slate-600 font-semibold focus:outline-none">
                <option value="">Tất cả phương thức</option>
                <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Chuyển khoản ngân hàng</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái duyệt</label>
            <select name="status" class="w-full p-2 border border-slate-200 rounded-xl text-xs bg-white text-slate-600 font-semibold focus:outline-none">
                <option value="">Tất cả trạng thái</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Thành công</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tra cứu nhanh</label>
            <div class="flex space-x-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập mã giao dịch..." class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none font-mono text-slate-700">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
                </div>
                <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-4 rounded-xl transition shadow-sm">Lọc</button>
                @if(request()->anyFilled(['start_date', 'end_date', 'method', 'status', 'search']))
                    <a href="{{ route('admin.payments.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 rounded-xl transition border border-slate-200/50 flex items-center justify-center">Xóa</a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="p-4 w-32">Mã giao dịch</th>
                    <th class="p-4 w-32">Mã lịch đặt</th>
                    <th class="p-4">Khách hàng đặt sân</th>
                    <th class="p-4">Số tiền hóa đơn</th>
                    <th class="p-4">Phương thức</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4">Ngày thanh toán</th>
                    <th class="p-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100 font-medium">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="p-4 font-bold text-slate-800 font-mono text-xs">{{ $payment->transaction_code ?? 'TXN-'.str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4 text-slate-400 font-mono text-xs">BK-{{ str_pad($payment->booking->id ?? 0, 3, '0', STR_PAD_LEFT) }}</td>
                    
                    <td class="p-4 flex items-center space-x-2 py-3.5">
                        <div class="w-6 h-6 bg-slate-100 text-slate-700 font-black rounded-full flex items-center justify-center text-[9px] uppercase border border-slate-200">
                            {{ Str::substr($payment->booking->customer->name ?? 'KH', 0, 2) }}
                        </div>
                        <span class="text-slate-700 font-bold">{{ $payment->booking->customer->name ?? 'Khách hệ thống' }}</span>
                    </td>
                    
                    <td class="p-4 font-black text-slate-800 font-mono">{{ number_format($payment->amount) }}đ</td>
                    <td class="p-4 text-slate-500 font-semibold">
                        🏦 Chuyển khoản ngân hàng
                    </td>
                    
                    <td class="p-4">
                        @if(in_array(strtolower($payment->status), ['success', 'paid', 'completed']))
                            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-100 block text-center w-32 tracking-wider">ĐÃ THANH TOÁN</span>
                        @elseif(in_array(strtolower($payment->status), ['pending', 'unpaid', 'processing']))
                            <span class="bg-[#eff6ff] text-[#3b82f6] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-blue-100 block text-center w-32 tracking-wider">CHỜ GIAO DỊCH</span>
                        @else
                            <span class="bg-[#fef2f2] text-[#ef4444] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-red-100 block text-center w-32 tracking-wider">ĐÃ HOÀN TIỀN</span>
                        @endif
                    </td>
                    
                    <td class="p-4 font-light text-slate-400 font-mono">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') : '---' }}</td>
                    
                    <td class="p-4 text-right space-x-2 text-xs font-bold">
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-blue-500 hover:bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 transition inline-block">
                            👁️ Xem
                        </a>
                        <button type="button" class="text-slate-400 hover:text-red-600 transition text-sm font-normal">⋮</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-slate-400 font-light">Không tìm thấy hóa đơn giao dịch nào phù hợp với bộ lọc.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-slate-50 border-t border-slate-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection