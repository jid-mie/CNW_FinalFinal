<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Owner Dashboard') }}
            </h2>
            <span class="bg-[#4ade80] text-[#0f172a] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Quản Lý Chủ Sân
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Yard Owner Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Fields -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tổng số sân</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $totalFields }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a]/70 font-semibold">
                    <span>Sân thể thao sở hữu</span>
                </div>
            </div>

            <!-- Bookings Today -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Đặt lịch hôm nay</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $todayBookings }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a]/70 font-semibold">
                    <span>Lượt đặt sân trong ngày</span>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Chờ xác nhận</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $pendingBookings }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-amber-600 font-bold">
                    <span>Yêu cầu đang chờ duyệt</span>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Doanh thu tháng này</p>
                        <h3 class="text-2xl font-bold font-heading text-[#4ade80] mt-1">{{ number_format($monthlyRevenue) }}đ</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 21h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a]/70 font-semibold">
                    <span>Tháng {{ now()->format('m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Booking & Court Management Workspace -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Recent Bookings List -->
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide">Đặt Lịch Gần Đây</h3>
                        <p class="text-xs text-[#45464d] mt-1">Danh sách 5 lượt đặt sân mới nhất của bạn (sắp xếp giảm dần theo ngày giờ sử dụng).</p>
                    </div>
                </div>

                <div class="divide-y divide-[#e2e8f0]">
                    @forelse ($recentBookings as $booking)
                        <div class="py-4 flex items-center justify-between first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-full bg-[#f8fafc] text-xs font-bold text-[#0f172a] flex items-center justify-center border border-[#e2e8f0]">
                                    {{ strtoupper(substr($booking->customer->name, 0, 2)) }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $booking->customer->name }}</h4>
                                    <p class="text-xs text-[#45464d] mt-0.5">{{ $booking->field->name }} | <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded">{{ $booking->field->sport->name }}</span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <span class="block text-sm font-semibold text-[#0f172a]">{{ $booking->timeSlot->formatted_slot }}</span>
                                    <span class="block text-xs text-[#45464d]">{{ $booking->booking_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-right min-w-[100px]">
                                    <span class="block text-sm font-bold text-[#0f172a]">{{ number_format($booking->total_price) }}đ</span>
                                    
                                    <!-- Status Badge -->
                                    @if ($booking->status === 'pending')
                                        <span class="inline-block text-[10px] font-extrabold uppercase px-2 py-0.5 bg-amber-100 text-amber-800 rounded">Chờ duyệt</span>
                                    @elseif ($booking->status === 'confirmed')
                                        <span class="inline-block text-[10px] font-extrabold uppercase px-2 py-0.5 bg-green-100 text-green-800 rounded">Đã duyệt</span>
                                    @elseif ($booking->status === 'completed')
                                        <span class="inline-block text-[10px] font-extrabold uppercase px-2 py-0.5 bg-blue-100 text-blue-800 rounded">Hoàn thành</span>
                                    @else
                                        <span class="inline-block text-[10px] font-extrabold uppercase px-2 py-0.5 bg-red-100 text-red-800 rounded">Đã hủy</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($booking->status === 'pending')
                                        <!-- Confirm Form -->
                                        <form action="{{ route('owner.bookings.confirm', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg transition-transform active:scale-90 shadow-sm" title="Duyệt">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <!-- Cancel Form -->
                                        <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1.5 bg-red-100 text-red-700 rounded-lg transition-transform active:scale-90" title="Từ chối/Hủy">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-[#45464d]">
                            Không có yêu cầu đặt lịch nào gần đây.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Side Quick Control -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide mb-4">Thao Tác Nhanh</h3>
                    
                    <a href="#" class="w-full mb-3 flex items-center justify-between p-3.5 bg-[#0f172a] text-[#4ade80] rounded-xl font-bold uppercase tracking-wider text-xs shadow-sm hover:bg-slate-800 transition-colors">
                        <span>Đăng ký thêm sân đấu mới</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </a>

                    <a href="#" class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                        <span>Cài đặt giờ hoạt động & Giá</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </a>
                </div>

                <div class="pt-6 border-t border-[#e2e8f0]">
                    <div class="flex items-center gap-3 text-xs bg-slate-50 border border-[#e2e8f0] p-3 rounded-lg text-[#45464d]">
                        <svg class="w-5 h-5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Cơ sở của bạn đã được kiểm duyệt hợp lệ từ hệ thống PlayManagement.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
