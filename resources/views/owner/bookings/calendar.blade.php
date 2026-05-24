<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Lịch Đặt Sân</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.bookings.calendar', ['year' => $year, 'month' => $month - 1]) }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors shadow-sm bg-white">
                    ← Tháng trước
                </a>
                <span class="text-sm font-bold text-[#0f172a] uppercase tracking-wider font-heading px-3 py-1 bg-slate-50 border border-[#e2e8f0] rounded-lg">
                    Tháng {{ date('m/Y', strtotime($dateFrom)) }}
                </span>
                <a href="{{ route('owner.bookings.calendar', ['year' => $year, 'month' => $month + 1]) }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors shadow-sm bg-white">
                    Tháng sau →
                </a>
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
                    <div class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-[#45464d] text-center bg-slate-50 border-r border-[#e2e8f0] last:border-r-0">{{ $day }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 divide-x divide-y divide-[#e2e8f0] border-t border-[#e2e8f0]">
                @php
                    $firstDayOfWeek = (int) $start->format('N') - 1; // Mon=1→0
                @endphp
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="p-3 bg-slate-50/50 min-h-[120px] border-t-0"></div>
                @endfor
                @for($d = clone $start; $d <= $end; $d->modify('+1 day'))
                    @php
                        $dateStr = $d->format('Y-m-d');
                        $dayBookings = $bookings->get($dateStr, collect());
                        $isToday = $dateStr === $today;
                    @endphp
                    <div class="p-3 min-h-[120px] transition-colors hover:bg-slate-50/40 {{ $isToday ? 'bg-green-50/30' : '' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold {{ $isToday ? 'text-[#4ade80] bg-[#0f172a] w-6 h-6 rounded-full flex items-center justify-center shadow-sm' : 'text-[#0f172a] bg-slate-100 w-6 h-6 rounded-full flex items-center justify-center border border-[#e2e8f0]' }}">
                                {{ $d->format('j') }}
                            </span>
                            @if($dayBookings->count() > 0)
                                <span class="text-[10px] font-bold text-[#0f172a] bg-[#4ade80] px-1.5 py-0.5 rounded shadow-sm">
                                    {{ $dayBookings->count() }}
                                </span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            @foreach($dayBookings->take(3) as $b)
                                <div class="text-[10px] px-1.5 py-1 rounded truncate border font-medium
                                    @if($b->status == 'confirmed') bg-blue-50 text-blue-700 border-blue-100
                                    @elseif($b->status == 'completed') bg-green-50 text-green-700 border-green-100
                                    @elseif($b->status == 'cancelled') bg-red-50 text-red-700 border-red-100
                                    @else bg-yellow-50 text-yellow-700 border-yellow-100
                                    @endif">
                                    <span class="font-bold">{{ $b->timeSlot?->start_time?->format('H:i') }}</span> {{ $b->customer?->name }}
                                </div>
                            @endforeach
                            @if($dayBookings->count() > 3)
                                <a href="{{ route('owner.bookings.index', ['date_from' => $dateStr, 'date_to' => $dateStr]) }}" class="block text-[10px] text-center font-bold text-[#0f172a] hover:underline mt-1">
                                    +{{ $dayBookings->count() - 3 }} đặt sân khác
                                </a>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <div class="flex items-center justify-between text-xs text-[#45464d] mt-4 bg-slate-50 border border-[#e2e8f0] p-3 rounded-lg">
            <span>Tổng số lượng đặt lịch trong tháng này: <strong class="text-[#0f172a] font-bold">{{ $bookings->flatten(1)->count() }}</strong> lượt</span>
            <div class="flex gap-3 text-[10px] font-bold uppercase tracking-wider">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-yellow-100 border border-yellow-200 rounded-sm"></span> Chờ duyệt</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-blue-100 border border-blue-200 rounded-sm"></span> Đã duyệt</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-green-100 border border-green-200 rounded-sm"></span> Hoàn tất</span>
            </div>
        </div>
    </div>
</x-app-layout>
