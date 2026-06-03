@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f1f5f9] min-h-screen">
    
    <!-- Breadcrumb -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-500 font-semibold">
            <span class="text-slate-400">Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> 
            <a href="{{ route('admin.bookings.index') }}" class="hover:text-slate-700 transition">Đặt lịch</a> 
            <span class="mx-1.5 text-slate-300">&gt;</span> 
            <span class="text-slate-700 font-bold underline cursor-pointer">Lịch đặt chờ phê duyệt</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase flex items-center gap-2">
                Danh sách chờ phê duyệt
                <span class="bg-yellow-100 text-yellow-800 text-xs font-black px-2.5 py-0.5 rounded-full border border-yellow-300 animate-pulse">
                    {{ $bookings->total() }} LƯỢT ĐỢI
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 font-semibold">Xem xét các yêu cầu đặt lịch mới, giải quyết chồng chéo lịch trước khi duyệt.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bookings.index') }}" class="bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition border border-slate-300 shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Xem Tất cả</span>
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

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-300/80 shadow-sm overflow-hidden font-sans">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                    <th class="p-4">Khách hàng</th>
                    <th class="p-4">Sân thể thao</th>
                    <th class="p-4">Môn thể thao</th>
                    <th class="p-4">Ngày đặt</th>
                    <th class="p-4">Khung giờ</th>
                    <th class="p-4">Giá thuê</th>
                    <th class="p-4 text-center">Thao tác duyệt nhanh</th>
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
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                @csrf
                                <button class="bg-[#10b981] text-white hover:bg-[#059669] text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition-colors">
                                    Duyệt ngay
                                </button>
                            </form>
                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Huỷ yêu cầu đặt lịch này?')">
                                @csrf
                                <button class="bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-200 transition-colors">
                                    Từ chối
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-500 font-semibold">Không có lịch đặt nào đang chờ phê duyệt.</td>
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
