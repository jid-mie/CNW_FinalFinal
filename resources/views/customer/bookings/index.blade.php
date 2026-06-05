<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-3xl text-slate-900 uppercase tracking-tight">
                    {{ __('Lịch Sử Đặt Sân') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Quản lý và theo dõi lịch đặt sân của bạn</p>
            </div>
            <div>
                <a href="{{ route('customer.bookings.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-900 text-[#3cd882] hover:bg-slate-800 rounded-2xl font-black uppercase tracking-wider text-xs shadow-sm transition-all duration-300 hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Đặt sân mới
                </a>
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

        <!-- Filter tabs -->
        <div class="mb-8 flex flex-wrap gap-2.5 bg-slate-100 p-1.5 rounded-2xl max-w-max">
            <a href="{{ route('customer.bookings.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all {{ !request('status') ? 'bg-slate-900 text-[#3cd882] shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                Tất cả
            </a>
            @foreach(['pending' => 'Chờ duyệt', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $val => $label)
                <a href="{{ route('customer.bookings.index', ['status' => $val]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all {{ request('status') === $val ? 'bg-slate-900 text-[#3cd882] shadow-sm' : 'text-slate-600 hover:text-slate-950' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Bookings List -->
        <div class="space-y-5">
            @forelse ($bookings as $booking)
                <div class="relative bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:border-[#3cd882]/30 hover:shadow-md transition-all duration-300 overflow-hidden">
                    <!-- Left status color stripe -->
                    @if ($booking->status === 'pending')
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>
                    @elseif ($booking->status === 'confirmed')
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#3cd882]"></div>
                    @elseif ($booking->status === 'completed')
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
                    @elseif ($booking->status === 'cancelled')
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                    @endif

                    <div class="pl-2 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="bg-slate-900 text-white text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border border-slate-800">
                                    {{ optional($booking->field->sport)->name ?? 'THỂ THAO' }}
                                </span>
                                
                                @php
                                    $statusMap = [
                                        'pending' => ['Chờ duyệt', 'bg-amber-100 text-amber-800 border-amber-200/50'],
                                        'confirmed' => ['Đã xác nhận', 'bg-[#3cd882]/10 text-emerald-800 border-[#3cd882]/20'],
                                        'completed' => ['Hoàn thành', 'bg-blue-50 text-blue-800 border-blue-200/50'],
                                        'cancelled' => ['Đã hủy', 'bg-rose-50 text-rose-800 border-rose-200/50'],
                                    ];
                                    [$sLabel, $sClass] = $statusMap[$booking->status] ?? ['Không xác định', 'bg-gray-100 text-gray-750'];
                                @endphp
                                <span class="text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border {{ $sClass }}">
                                    {{ $sLabel }}
                                </span>

                                @if ($booking->payment && $booking->payment->status === 'paid')
                                    <span class="bg-emerald-50 text-emerald-700 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-emerald-200">ĐÃ THANH TOÁN ({{ strtoupper($booking->payment->method) }})</span>
                                @elseif ($booking->payment && $booking->payment->status === 'pending')
                                    <span class="bg-amber-50 text-amber-700 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-amber-200">CHỜ THANH TOÁN</span>
                                @elseif ($booking->status !== 'cancelled')
                                    <span class="bg-slate-100 text-slate-600 text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md border border-slate-200">CHƯA THANH TOÁN</span>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-extrabold text-lg text-slate-900 tracking-tight leading-tight">{{ $booking->field->name }}</h4>
                                <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5 font-medium">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $booking->field->address }}
                                </p>
                            </div>

                            @if ($booking->note)
                                <div class="bg-slate-50 rounded-xl p-3 text-xs text-slate-600 border border-slate-100 font-medium">
                                    <span class="text-slate-400 font-bold">Ghi chú:</span> "{{ $booking->note }}"
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-row md:flex-col md:items-end justify-between md:justify-center gap-4 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                            <div class="text-left md:text-right">
                                <span class="block text-base font-black text-slate-900">
                                    {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                                </span>
                                <span class="block text-xs font-semibold text-slate-400 uppercase mt-0.5">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                @if ((!$booking->payment || $booking->payment->status !== 'paid') && !in_array($booking->status, ['cancelled', 'completed']))
                                    <button onclick="openVietQRModal({{ $booking->id }}, '{{ addslashes($booking->field->name) }}', {{ (int) $booking->total_price }})"
                                            class="text-xs font-extrabold text-slate-900 bg-[#3cd882] hover:bg-[#2fc473] px-4 py-2 rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer">
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
                </div>
            @empty
                <div class="text-center py-20 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-900">Không tìm thấy lịch đặt nào</p>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Hãy đặt lịch đấu mới và bắt đầu luyện tập, thi đấu ngay hôm nay!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($bookings->hasPages())
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    @include('customer.partials.vietqr-modal')
</x-app-layout>
