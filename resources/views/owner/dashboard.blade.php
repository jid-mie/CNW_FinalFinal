<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                Owner Dashboard
            </h2>
            <span class="bg-[#4ade80] text-[#0f172a] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Quản Lý Chủ Sân
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tổng sân</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $stats['total_fields'] }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 text-xs text-[#006d36] font-bold">
                    {{ $stats['active_fields'] }} sân đang hoạt động
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Đặt lịch hôm nay</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $stats['today_bookings'] }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Chờ duyệt</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $stats['pending_bookings'] }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Doanh thu hôm nay</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($stats['today_revenue'], 0, ',', '.') }}đ</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 21h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <a href="{{ route('owner.bookings.calendar') }}" class="block">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Xem lịch</p>
                            <h3 class="text-xl font-bold font-heading text-[#0f172a] mt-1">Calendar</h3>
                        </div>
                        <div class="p-3 bg-[#0f172a] text-[#4ade80] rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Pending Bookings -->
            <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide">Yêu Cầu Chờ Duyệt</h3>
                    <a href="{{ route('owner.bookings.pending') }}" class="text-xs font-semibold text-[#0f172a] underline">Xem tất cả</a>
                </div>
                @if($pendingBookings->count() > 0)
                    <div class="divide-y divide-[#e2e8f0]">
                        @foreach($pendingBookings as $booking)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-full bg-[#f8fafc] text-xs font-bold text-[#0f172a] flex items-center justify-center border border-[#e2e8f0]">
                                    {{ strtoupper(substr($booking->customer?->name ?? 'KH', 0, 2)) }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $booking->customer?->name ?? 'N/A' }}</h4>
                                    <p class="text-xs text-[#45464d] mt-0.5">{{ $booking->field?->name }} | {{ $booking->timeSlot?->start_time?->format('H:i') }} - {{ $booking->timeSlot?->end_time?->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-[#45464d]">{{ $booking->booking_date->format('d/m') }}</span>
                                <form action="{{ route('owner.bookings.confirm', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg hover:bg-green-400 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></button>
                                </form>
                                <form action="{{ route('owner.bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Từ chối đặt lịch này?')">
                                    @csrf
                                    <button class="p-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-[#45464d] py-4 text-center">Không có yêu cầu chờ duyệt</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide mb-4">Thao Tác Nhanh</h3>
                <a href="{{ route('owner.fields.create') }}" class="w-full mb-3 flex items-center justify-between p-3.5 bg-[#0f172a] text-[#4ade80] rounded-xl font-bold uppercase tracking-wider text-xs shadow-sm hover:bg-slate-800 transition-colors">
                    <span>Thêm sân mới</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </a>
                <a href="{{ route('owner.bookings.pending') }}" class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                    <span>Xem lịch đặt</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </a>
                <a href="{{ route('owner.revenue.index') }}" class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                    <span>Báo cáo doanh thu</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </a>
                <div class="pt-4 mt-2 border-t border-[#e2e8f0]">
                    <div class="flex items-center gap-3 text-xs bg-slate-50 border border-[#e2e8f0] p-3 rounded-lg text-[#45464d]">
                        <svg class="w-5 h-5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Cơ sở của bạn đã được kiểm duyệt hợp lệ.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>