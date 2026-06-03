<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('My Dashboard') }}
            </h2>
            <span class="bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Thành Viên: {{ auth()->user()->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        @if (session('success'))
            <div class="mb-6 bg-[#dcfce7] border border-[#bbf7d0] text-[#15803d] rounded-xl p-4 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Customer Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Số trận sắp đá</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $upcomingBookingsCount }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a] font-semibold">
                    <span>Lịch thi đấu đã đăng ký và xác nhận</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Trận đã hoàn thành</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $completedBookingsCount }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Thành tích đặt sân thi đấu</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tài khoản Play Wallet</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">0đ</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a] hover:underline cursor-pointer font-bold">
                    <span>Nạp thêm tiền vào ví (Chưa khả dụng)</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Điểm tích lũy (Stars)</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">0</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#45464d] font-bold">
                    <span>Hạng Bạc (Silver Member)</span>
                </div>
            </div>
        </div>

        <!-- Bookings & Arena Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: List of active bookings -->
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide">Lịch Đặt Sân Của Tôi</h3>
                        <p class="text-xs text-[#45464d] mt-1">Xem trạng thái chi tiết lịch hẹn bóng đá, cầu lông sắp tới.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($bookings as $booking)
                        <div class="border border-[#e2e8f0] rounded-xl p-4 bg-[#f8fafc] flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="bg-[#e2e8f0] text-[#0f172a] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                                        {{ optional($booking->field->sport)->name ?? 'THỂ THAO' }}
                                    </span>
                                    
                                    @if ($booking->status === 'pending')
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">CHỜ DUYỆT</span>
                                    @elseif ($booking->status === 'confirmed')
                                        <span class="bg-[#dcfce7] text-[#15803d] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">ĐÃ XÁC NHẬN</span>
                                    @elseif ($booking->status === 'completed')
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">HOÀN THÀNH</span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">ĐÃ HỦY</span>
                                    @endif

                                    @if ($booking->payment)
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">ĐÃ THANH TOÁN ({{ strtoupper($booking->payment->method) }})</span>
                                    @else
                                        @if ($booking->status !== 'cancelled')
                                            <span class="bg-gray-100 text-gray-700 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">CHƯA THANH TOÁN</span>
                                        @endif
                                    @endif
                                </div>
                                <h4 class="font-bold text-base text-[#0f172a] mt-2">{{ $booking->field->name }}</h4>
                                <p class="text-xs text-[#45464d] mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $booking->field->address }}
                                </p>
                                @if ($booking->note)
                                    <p class="text-xs text-[#45464d] italic mt-1">Ghi chú: "{{ $booking->note }}"</p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between md:flex-col md:items-end gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-[#e2e8f0]">
                                <div class="text-left md:text-right">
                                    <span class="block text-sm font-bold text-[#0f172a]">
                                        {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                                    </span>
                                    <span class="block text-xs text-[#45464d]">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if (in_array($booking->status, ['pending', 'confirmed']))
                                        <form action="{{ route('customer.bookings.cancel.web', $booking) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch đặt này không?')" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline bg-transparent border-0 p-0 cursor-pointer">
                                                Hủy đặt lịch
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <span class="text-xs font-bold text-[#0f172a] bg-white border border-[#e2e8f0] px-3 py-1.5 rounded-lg shadow-sm select-none">
                                        {{ number_format($booking->total_price) }}đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 border border-[#e2e8f0] rounded-xl bg-[#f8fafc]">
                            <svg class="w-12 h-12 text-[#94a3b8] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-semibold text-[#0f172a]">Chưa có lịch đặt sân nào</p>
                            <p class="text-xs text-[#45464d] mt-1">Hãy đăng ký đặt sân để bắt đầu các trận thi đấu hấp dẫn nhé!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side Call To Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide mb-4">Hoạt Động</h3>
                    
                    <button onclick="alert('Tính năng đang được phát triển trên phiên bản web. Bạn có thể sử dụng API để đặt sân ngay!')" class="w-full mb-3 flex items-center justify-between p-3.5 bg-[#0f172a] text-[#4ade80] rounded-xl font-bold uppercase tracking-wider text-xs shadow-sm hover:bg-slate-800 transition-colors">
                        <span>Đặt Sân Đấu Ngay</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <button onclick="alert('Tính năng đang được phát triển trên phiên bản web!')" class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                        <span>Lịch sử đặt sân</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>

                <div class="pt-6 border-t border-[#e2e8f0]">
                    <p class="text-xs text-[#45464d] leading-relaxed">
                        Bạn muốn tìm thêm đồng đội đá giao hữu? Hãy tham gia ngay các group thể thao kết nối thành viên PlayManagement!
                    </p>
                    <a href="#" class="inline-block mt-3 text-xs font-bold text-[#0f172a] hover:underline">Tìm nhóm giao lưu thể thao &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
