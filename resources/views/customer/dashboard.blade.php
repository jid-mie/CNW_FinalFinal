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

        <!-- Customer Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat 1 -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Số trận sắp đá</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">{{ $upcomingBookingsCount }}</h3>
                    </div>
                    <div class="p-4 bg-slate-50 text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-[11px] text-slate-400 font-semibold uppercase tracking-wider">
                    <span>Lịch thi đấu đã xác nhận</span>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đã hoàn thành</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">{{ $completedBookingsCount }}</h3>
                    </div>
                    <div class="p-4 bg-slate-50 text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-[11px] text-emerald-600 font-bold uppercase tracking-wider">
                    <span>Thành tích thi đấu xuất sắc</span>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ví Play Wallet</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">0đ</h3>
                    </div>
                    <div class="p-4 bg-slate-50 text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-[11px] text-slate-400 font-bold uppercase tracking-wider group-hover:text-[#3cd882] transition-colors">
                    <span>Nạp thẻ thành viên (Chưa mở)</span>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm transition-all duration-300 hover:border-[#3cd882] hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Điểm tích lũy (Stars)</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight group-hover:text-[#3cd882] transition-colors">0</h3>
                    </div>
                    <div class="p-4 bg-slate-50 text-slate-700 rounded-2xl group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882] transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-[11px] text-amber-600 font-bold uppercase tracking-wider">
                    <span>Hạng Bạc (Silver Member)</span>
                </div>
            </div>
        </div>

        <!-- Bookings & Arena Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: List of active bookings -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-wide">Lịch Đặt Sân Gần Đây</h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Theo dõi và thực hiện thanh toán các trận đấu bóng đá, cầu lông của bạn.</p>
                    </div>
                    <a href="{{ route('customer.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 bg-slate-50 hover:bg-slate-100 px-3.5 py-2 rounded-xl transition-all border border-slate-100">
                        Xem tất cả lịch đặt
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="space-y-5">
                    @forelse ($bookings as $booking)
                        <div class="relative border border-slate-100 rounded-2xl p-5 bg-slate-50/50 hover:bg-white hover:border-[#3cd882]/30 hover:shadow-md transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6 overflow-hidden">
                            <!-- Left border status color -->
                            @if ($booking->status === 'pending')
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>
                            @elseif ($booking->status === 'confirmed')
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#3cd882]"></div>
                            @elseif ($booking->status === 'completed')
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
                            @elseif ($booking->status === 'cancelled')
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                            @endif

                            <div class="pl-2 space-y-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="bg-slate-900 text-white text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border border-slate-800">
                                        {{ optional($booking->field->sport)->name ?? 'THỂ THAO' }}
                                    </span>
                                    
                                    @if ($booking->status === 'pending')
                                        <span class="bg-amber-100 text-amber-800 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md">CHỜ DUYỆT</span>
                                    @elseif ($booking->status === 'confirmed')
                                        <span class="bg-[#3cd882]/10 text-emerald-800 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-[#3cd882]/20">ĐÃ XÁC NHẬN</span>
                                    @elseif ($booking->status === 'completed')
                                        <span class="bg-blue-50 text-blue-800 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md">HOÀN THÀNH</span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="bg-rose-50 text-rose-800 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md">ĐÃ HỦY</span>
                                    @endif

                                    @if ($booking->payment && $booking->payment->status === 'paid')
                                        <span class="bg-emerald-50 text-emerald-700 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-emerald-200">ĐÃ THANH TOÁN ({{ strtoupper($booking->payment->method) }})</span>
                                    @elseif ($booking->payment && $booking->payment->status === 'pending')
                                        <span class="bg-amber-50 text-amber-700 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-amber-200">CHỜ THANH TOÁN</span>
                                    @else
                                        @if ($booking->status !== 'cancelled')
                                            <span class="bg-slate-100 text-slate-600 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-slate-200">CHƯA THANH TOÁN</span>
                                        @endif
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-extrabold text-lg text-slate-900 tracking-tight leading-tight">{{ $booking->field->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5 font-medium">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $booking->field->address }}
                                    </p>
                                </div>

                                @if ($booking->note)
                                    <div class="bg-white/80 rounded-xl p-2.5 text-xs text-slate-600 border border-slate-100 font-medium">
                                        <span class="text-slate-400 font-bold">Ghi chú:</span> "{{ $booking->note }}"
                                    </div>
                                @endif
                            </div>

                            <div class="pl-2 md:pl-0 flex flex-row md:flex-col md:items-end justify-between md:justify-center gap-4 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                                <div class="text-left md:text-right">
                                    <span class="block text-base font-black text-slate-900">
                                        {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                                    </span>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase mt-0.5">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    @if ($booking->status !== 'cancelled' && (!$booking->payment || $booking->payment->status !== 'paid'))
                                        <button onclick="openVietQRModal({{ $booking->id }}, '{{ $booking->field->name }}', {{ (int) $booking->total_price }})" class="text-xs font-extrabold text-slate-900 bg-[#3cd882] hover:bg-[#2fc473] px-4 py-2 rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer">
                                            Thanh toán VietQR
                                        </button>
                                    @endif

                                    @if (in_array($booking->status, ['pending', 'confirmed']))
                                        <form action="{{ route('customer.bookings.cancel.web', $booking) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch đặt này không?')" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-transparent border-0 p-0 cursor-pointer">
                                                Hủy lịch
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <span class="text-xs font-extrabold text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs select-none">
                                        {{ number_format($booking->total_price) }}đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-900">Chưa có lịch đặt sân nào</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Hãy đặt lịch đấu và trải nghiệm dịch vụ tiện ích, chuyên nghiệp cùng chúng tôi!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side Call To Actions -->
            <div class="space-y-6">
                <!-- Action Cards -->
                <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden group">
                    <!-- Decorative background glow -->
                    <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-[#3cd882]/10 blur-3xl group-hover:bg-[#3cd882]/20 transition-all duration-500"></div>
                    
                    <h3 class="text-xl font-black uppercase tracking-wide mb-6">Thao tác nhanh</h3>
                    
                    <div class="space-y-4 relative z-10">
                        <a href="{{ route('customer.bookings.create') }}" class="w-full flex items-center justify-between p-4 bg-[#3cd882] text-slate-950 hover:bg-[#2fc473] rounded-2xl font-black uppercase tracking-wider text-xs shadow-sm transition-all duration-300 hover:scale-[1.02]">
                            <span>Đặt Sân Đấu Ngay</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </a>

                        <a href="{{ route('customer.bookings.index') }}" class="w-full flex items-center justify-between p-4 bg-slate-800 text-white hover:bg-slate-700/80 rounded-2xl font-black uppercase tracking-wider text-xs border border-slate-700/50 transition-all duration-300 hover:scale-[1.02]">
                            <span>Lịch sử đặt sân</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-800 text-xs text-slate-400 leading-relaxed font-medium">
                        Bạn cần tìm đối đá giao hữu hay ghép kèo tập luyện? Tham gia ngay cộng đồng PlayManagement để kết nối giao lưu thể thao.
                    </div>
                </div>

                <!-- Info Card -->
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
