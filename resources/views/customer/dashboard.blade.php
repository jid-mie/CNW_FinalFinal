<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-3xl text-slate-900 uppercase tracking-tight">
                    {{ __('Bảng Điều Khiển') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Khu vực dành cho hội viên</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl shadow-sm border border-slate-800">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#3cd882] animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-wider">Hội viên: {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-[#3cd882] text-emerald-950 rounded-r-xl p-4 text-sm font-semibold flex items-center gap-3 shadow-sm transition-all duration-300">
                <div class="p-1.5 bg-[#3cd882]/20 text-emerald-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-950 rounded-r-xl p-4 text-sm font-semibold flex items-center gap-3 shadow-sm transition-all duration-300">
                <div class="p-1.5 bg-rose-500/20 text-rose-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Compact Dashboard Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 uppercase tracking-wide">Thống Kê Nhanh</h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Tổng quan trạng thái đặt sân của bạn trong thời gian gần đây.</p>
                        </div>
                        <a href="{{ route('customer.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 bg-slate-50 hover:bg-slate-100 px-3.5 py-2 rounded-xl transition-all border border-slate-100">
                            Xem chi tiết
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Số trận sắp thi đấu</p>
                                    <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">{{ $upcomingBookingsCount }}</h3>
                                </div>
                                <div class="p-4 bg-white text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-[11px] text-slate-400 font-semibold uppercase tracking-wider">
                                <span>Lịch thi đấu đã xác nhận</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đã hoàn thành</p>
                                    <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">{{ $completedBookingsCount }}</h3>
                                </div>
                                <div class="p-4 bg-white text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-[11px] text-emerald-600 font-bold uppercase tracking-wider">
                                <span>Thành tích thi đấu xuất sắc</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ví Play Wallet</p>
                                    <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">0đ</h3>
                                </div>
                                <div class="p-4 bg-white text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-[11px] text-slate-400 font-bold uppercase tracking-wider group-hover:text-[#3cd882] transition-colors">
                                <span>Nạp thẻ thành viên (Chưa mở)</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Điểm tích lũy (Stars)</p>
                                    <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">0</h3>
                                </div>
                                <div class="p-4 bg-white text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-[11px] text-amber-600 font-bold uppercase tracking-wider">
                                <span>Hạng Bạc (Silver Member)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 uppercase tracking-wide">Lịch Đặt Gần Đây</h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Theo dõi nhanh các lịch đặt sân mới nhất của bạn.</p>
                        </div>
                        <a href="{{ route('customer.bookings.index') }}" class="text-xs font-bold text-slate-900 bg-slate-50 hover:bg-slate-100 px-3.5 py-2 rounded-xl transition-all border border-slate-100">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($bookings as $booking)
                            @php
                                $statusMap = [
                                    'pending' => ['CHỜ DUYỆT', 'bg-amber-100 text-amber-800'],
                                    'confirmed' => ['ĐÃ XÁC NHẬN', 'bg-emerald-100 text-emerald-800'],
                                    'completed' => ['HOÀN THÀNH', 'bg-blue-100 text-blue-800'],
                                    'cancelled' => ['ĐÃ HỦY', 'bg-rose-100 text-rose-800'],
                                ];
                                [$statusLabel, $statusClass] = $statusMap[$booking->status] ?? [strtoupper($booking->status), 'bg-slate-100 text-slate-700'];
                            @endphp
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ optional($booking->field)->name ?? 'Sân đã xóa' }}</p>
                                    <p class="text-xs text-slate-500 mt-1 font-semibold">
                                        {{ optional($booking->field?->sport)->name ?? 'Thể thao' }} · {{ \Illuminate\Support\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                        @if ($booking->timeSlot)
                                            · {{ substr($booking->timeSlot->start_time, 0, 5) }} - {{ substr($booking->timeSlot->end_time, 0, 5) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-3 py-1 text-[10px] font-black tracking-wider {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 font-semibold">Bạn chưa có lịch đặt sân nào.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-[#3cd882]/10 blur-3xl group-hover:bg-[#3cd882]/20 transition-all duration-500"></div>

                    <h3 class="text-xl font-black uppercase tracking-wide mb-4">Đặt sân nhanh</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium max-w-xl">Bắt đầu lịch đặt mới trong vài thao tác, giữ trải nghiệm gọn và nhất quán với toàn bộ hệ thống.</p>

                    <div class="mt-6 flex flex-wrap gap-3 relative z-10">
                        <a href="{{ route('customer.bookings.create') }}" class="inline-flex items-center justify-between gap-3 px-5 py-3.5 bg-[#3cd882] text-slate-950 hover:bg-[#2fc473] rounded-2xl font-black uppercase tracking-wider text-xs shadow-sm transition-all duration-300 hover:scale-[1.02]">
                            <span>Đặt Sân Đấu Ngay</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </a>

                        <a href="{{ route('customer.bookings.create') }}" class="inline-flex items-center justify-between gap-3 px-5 py-3.5 bg-slate-800 text-white hover:bg-slate-700/80 rounded-2xl font-black uppercase tracking-wider text-xs border border-slate-700/50 transition-all duration-300 hover:scale-[1.02]">
                            <span>Tạo lịch mới</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Quy định đặt & hủy sân</h4>
                    <ul class="text-xs text-slate-500 space-y-2.5 leading-relaxed font-medium">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#3cd882] mt-1.5 shrink-0"></span>
                            <span>Bạn có thể hủy đặt sân miễn phí trước giờ thi đấu tối thiểu <b>6 tiếng</b>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#3cd882] mt-1.5 shrink-0"></span>
                            <span>Các đặt sân chưa thanh toán sẽ tự động hủy nếu không ghi nhận giao dịch trong vòng <b>15 phút</b>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#3cd882] mt-1.5 shrink-0"></span>
                            <span>Vui lòng xuất trình lịch đặt sân tại quầy tiếp đón trước giờ đấu <b>10 phút</b>.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @include('customer.partials.vietqr-modal')
</x-app-layout>
