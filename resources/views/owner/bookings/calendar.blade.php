<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Lịch Đặt Sân</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.bookings.calendar', ['year' => $year, 'month' => $month - 1]) }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold hover:bg-slate-50">← Tháng trước</a>
                <span class="text-sm font-bold text-[#0f172a]">{{ date('m/Y', strtotime($dateFrom)) }}</span>
                <a href="{{ route('owner.bookings.calendar', ['year' => $year, 'month' => $month + 1]) }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold hover:bg-slate-50">Tháng sau →</a>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            @php
                $start = new DateTime($dateFrom);
                $end = new DateTime($dateTo);
                $today = today()->format('Y-m-d');
            @endphp
            <div class="grid grid-cols-7 border-b border-[#e2e8f0]">
                @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $day)
                    <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-[#45464d] text-center bg-slate-50">{{ $day }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7">
                @php
                    $firstDayOfWeek = (int) $start->format('N') - 1; // Mon=1→0
                @endphp
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="p-2 bg-gray-50/50 min-h-[100px]"></div>
                @endfor
                @for($d = clone $start; $d <= $end; $d->modify('+1 day'))
                    @php
                        $dateStr = $d->format('Y-m-d');
                        $dayBookings = $bookings->get($dateStr, collect());
                        $isToday = $dateStr === $today;
                    @endphp
                    <div class="p-2 border border-[#e2e8f0] min-h-[100px] {{ $isToday ? 'bg-[#f0fdf4]' : '' }}">
                        <div class="text-xs font-bold {{ $isToday ? 'text-[#4ade80] bg-[#0f172a] w-6 h-6 rounded-full flex items-center justify-center' : 'text-[#0f172a]' }} mb-1">
                            {{ $d->format('j') }}
                        </div>
                        @foreach($dayBookings->take(3) as $b)
                            <div class="text-xs px-1 py-0.5 mb-0.5 rounded truncate
                                @if($b->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($b->status == 'completed') bg-[#4ade80] text-[#0f172a]
                                @elseif($b->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $b->timeSlot?->start_time?->format('H:i') }} {{ $b->customer?->name }}
                            </div>
                        @endforeach
                        @if($dayBookings->count() > 3)
                            <div class="text-xs text-[#45464d]">+{{ $dayBookings->count() - 3 }} thêm</div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
        <p class="text-xs text-[#45464d] mt-3">Tổng: <strong>{{ $bookings->flatten(1)->count() }}</strong> đặt lịch trong tháng</p>
    </div>
</x-app-layout>
