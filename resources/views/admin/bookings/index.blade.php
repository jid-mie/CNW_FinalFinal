@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f1f5f9] min-h-screen">
    
    <!-- Breadcrumb -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-500 font-semibold">
            <span class="text-slate-400">Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> 
            <span class="text-slate-700 underline cursor-pointer">Quản lý Đặt lịch</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase flex items-center gap-2">
                Danh sách Đặt lịch toàn hệ thống
                <span class="bg-[#10b981]/10 text-[#047857] text-[11px] font-black px-2.5 py-0.5 rounded-full border border-[#10b981]/20">
                    {{ $bookings->total() }} LƯỢT ĐẶT
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 font-semibold">Theo dõi, phê duyệt, hủy bỏ và quản trị toàn bộ giao dịch đặt sân chơi thể thao của khách hàng.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bookings.pending') }}" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                <span>Chờ phê duyệt</span>
            </a>
            <a href="{{ route('admin.bookings.calendar') }}" class="bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition border border-slate-300 shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Xem Lịch biểu</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-green-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-5 rounded-2xl border border-slate-300/80 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tìm kiếm khách hàng, sđt, tên sân...</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="VD: Nguyễn Văn A, 098..., Sân Mỹ Đình" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs bg-slate-50 text-slate-900 font-medium focus:outline-none focus:border-slate-500 focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sân thể thao</label>
                <select name="field_id" class="w-full p-2.5 border border-slate-300 rounded-xl text-xs bg-slate-50 text-slate-900 font-bold focus:outline-none focus:border-slate-500 focus:bg-white transition-colors">
                    <option value="">Tất cả sân</option>
                    @foreach($fields as $f)
                        <option value="{{ $f->id }}" {{ request('field_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Trạng thái</label>
                <select name="status" class="w-full p-2.5 border border-slate-300 rounded-xl text-xs bg-slate-50 text-slate-900 font-bold focus:outline-none focus:border-slate-500 focus:bg-white transition-colors">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full p-2.5 border border-slate-300 rounded-xl text-xs bg-slate-50 text-slate-900 font-bold focus:outline-none focus:border-slate-500 focus:bg-white transition-colors">
            </div>
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Đến ngày</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full p-2.5 border border-slate-300 rounded-xl text-xs bg-slate-50 text-slate-900 font-bold focus:outline-none focus:border-slate-500 focus:bg-white transition-colors">
                </div>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white hover:text-[#4ade80] text-xs font-bold px-4 py-3 rounded-xl transition shadow-sm flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-300/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                    <th class="p-4">Khách hàng</th>
                    <th class="p-4">Sân thể thao</th>
                    <th class="p-4">Bộ môn</th>
                    <th class="p-4">Ngày đặt</th>
                    <th class="p-4">Khung giờ</th>
                    <th class="p-4">Tổng tiền</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-800 divide-y divide-slate-200 font-semibold">
                @forelse($bookings as $booking)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-slate-900 text-sm">{{ $booking->customer->name ?? 'N/A' }}</div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">{{ $booking->customer->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="p-4 font-bold text-slate-900 text-sm">
                        <a href="{{ route('admin.fields.show', $booking->field_id) }}" class="hover:text-[#10b981] transition-colors underline decoration-slate-300">
                            {{ $booking->field->name ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="p-4 text-slate-700 font-bold">
                        {{ $booking->field->sport->name ?? 'N/A' }}
                    </td>
                    <td class="p-4 text-slate-700">
                        {{ $booking->booking_date->format('d/m/Y') }}
                    </td>
                    <td class="p-4 font-mono font-bold text-slate-900">
                        {{ $booking->timeSlot ? $booking->timeSlot->start_time->format('H:i') . ' - ' . $booking->timeSlot->end_time->format('H:i') : 'N/A' }}
                    </td>
                    <td class="p-4 font-black text-slate-900 font-mono text-sm">
                        {{ number_format($booking->total_price) }}đ
                    </td>
                    <td class="p-4">
                        @if($booking->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-[10px] font-black px-2.5 py-1 rounded-full border border-yellow-300 tracking-wider">CHỜ DUYỆT</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-2.5 py-1 rounded-full border border-blue-300 tracking-wider">ĐÃ XÁC NHẬN</span>
                        @elseif($booking->status === 'completed')
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-300 tracking-wider">HOÀN THÀNH</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-black px-2.5 py-1 rounded-full border border-red-300 tracking-wider">ĐÃ HỦY</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-[#10b981] hover:bg-[#059669] text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition-colors" title="Phê duyệt">
                                        Duyệt
                                    </button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận từ chối và hủy lượt đặt này?')">
                                    @csrf
                                    <button class="bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-200 transition-colors" title="Từ chối/Hủy">
                                        Từ chối
                                    </button>
                                </form>
                            @elseif($booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.checkin', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận hoàn thành đặt sân và cho phép check-in?')">
                                    @csrf
                                    <button class="bg-slate-900 text-[#4ade80] hover:bg-slate-800 text-[11px] font-bold px-3.5 py-1.5 rounded-lg transition-colors shadow-sm">
                                        Check-in
                                    </button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Huỷ đặt lịch này?')">
                                    @csrf
                                    <button class="bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-200 transition-colors" title="Từ chối/Hủy">
                                        Hủy đặt
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-500 text-[11px] font-bold">Không thao tác</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-slate-500 font-semibold">Không có lịch đặt sân thể thao nào tồn tại.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($bookings->hasPages())
        <div class="p-4 bg-slate-50 border-t border-slate-300">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
