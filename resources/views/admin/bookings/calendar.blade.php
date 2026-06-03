@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f1f5f9] min-h-screen">
    
    <!-- Breadcrumb -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-500 font-semibold">
            <span class="text-slate-400">Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> 
            <a href="{{ route('admin.bookings.index') }}" class="hover:text-slate-700 transition">Đặt lịch</a> 
            <span class="mx-1.5 text-slate-300">&gt;</span> 
            <span class="text-slate-700 font-bold underline cursor-pointer">Lịch biểu hoạt động</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase flex items-center gap-2">
                Lịch biểu hoạt động
                <span class="bg-[#10b981]/10 text-[#047857] text-[11px] font-black px-2.5 py-0.5 rounded-full border border-[#10b981]/20 uppercase">
                    THÁNG {{ date('m/Y', strtotime($dateFrom)) }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 font-semibold">Xem biểu đồ thời gian và mật độ đặt sân theo từng ngày trong tháng.</p>
        </div>
        
        <!-- Bộ lọc sân và chuyển đổi tháng -->
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('admin.bookings.calendar') }}" class="flex items-center gap-2 bg-white p-1 rounded-xl border border-slate-300">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <select name="field_id" onchange="this.form.submit()" class="p-1.5 bg-transparent border-0 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:ring-0">
                    <option value="">Tất cả sân thể thao</option>
                    @foreach($fields as $f)
                        <option value="{{ $f->id }}" {{ request('field_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </form>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('admin.bookings.calendar', ['year' => $year, 'month' => $month - 1, 'field_id' => request('field_id')]) }}" class="bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold px-3 py-2 rounded-xl transition border border-slate-300 shadow-sm flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    Trước
                </a>
                <span class="text-xs font-black text-slate-900 bg-slate-200 border border-slate-300 px-3.5 py-2 rounded-xl">
                    {{ date('m/Y', strtotime($dateFrom)) }}
                </span>
                <a href="{{ route('admin.bookings.calendar', ['year' => $year, 'month' => $month + 1, 'field_id' => request('field_id')]) }}" class="bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold px-3 py-2 rounded-xl transition border border-slate-300 shadow-sm flex items-center gap-1">
                    Sau
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-2xl border border-slate-300/80 shadow-sm overflow-hidden font-sans">
        @php
            $start = new DateTime($dateFrom);
            $end = new DateTime($dateTo);
            $today = today()->format('Y-m-d');
        @endphp
        
        <div class="grid grid-cols-7 border-b border-slate-300">
            @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $day)
                <div class="px-3 py-3.5 text-[11px] font-black uppercase tracking-wider text-slate-700 text-center bg-slate-100 border-r border-slate-200 last:border-r-0">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 divide-x divide-y divide-slate-200 border-t border-slate-200">
            @php
                $firstDayOfWeek = (int) $start->format('N') - 1; // Mon=1→0
            @endphp
            
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="p-3 bg-slate-50/50 min-h-[120px]"></div>
            @endfor

            @for($d = clone $start; $d <= $end; $d->modify('+1 day'))
                @php
                    $dateStr = $d->format('Y-m-d');
                    $dayBookings = $bookings->get($dateStr, collect());
                    $isToday = $dateStr === $today;
                @endphp
                <div class="p-3 min-h-[120px] transition-colors hover:bg-slate-50/60 {{ $isToday ? 'bg-emerald-50/30' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black {{ $isToday ? 'text-white bg-[#0f172a] w-6 h-6 rounded-full flex items-center justify-center shadow-md font-mono' : 'text-slate-800 bg-slate-100 w-6 h-6 rounded-full flex items-center justify-center border border-slate-300 font-mono' }}">
                            {{ $d->format('j') }}
                        </span>
                        @if($dayBookings->count() > 0)
                            <span class="text-[9px] font-black text-slate-800 bg-[#3cd882] px-2 py-0.5 rounded-full shadow-sm">
                                {{ $dayBookings->count() }} ĐƠN
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        @foreach($dayBookings->take(3) as $b)
                            <div class="text-[9px] px-1.5 py-0.5 rounded font-black truncate border
                                @if($b->status === 'confirmed') bg-blue-100 text-blue-800 border-blue-300
                                @elseif($b->status === 'completed') bg-emerald-100 text-emerald-800 border-emerald-300
                                @elseif($b->status === 'cancelled') bg-red-100 text-red-800 border-red-300
                                @else bg-yellow-100 text-yellow-800 border-yellow-300
                                @endif" title="{{ $b->field->name ?? 'N/A' }}: {{ $b->customer->name ?? 'N/A' }}">
                                <span class="font-mono font-bold">{{ $b->timeSlot ? $b->timeSlot->start_time->format('H:i') : '' }}</span> 
                                <span>{{ $b->customer->name ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                        
                        @if($dayBookings->count() > 3)
                            <a href="{{ route('admin.bookings.index', ['date_from' => $dateStr, 'date_to' => $dateStr, 'field_id' => request('field_id')]) }}" class="block text-[9px] text-center font-bold text-slate-600 hover:text-[#10b981] mt-1 underline">
                                +{{ $dayBookings->count() - 3 }} đơn khác
                            </a>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Summary Box -->
    <div class="flex flex-col md:flex-row gap-3 items-center justify-between text-xs text-slate-700 mt-4 bg-slate-100 border border-slate-300 p-4 rounded-xl shadow-sm">
        <span>Tổng đặt lịch tháng này: <strong class="text-slate-900 font-black font-mono text-sm">{{ $bookings->flatten(1)->count() }} lượt</strong></span>
        <div class="flex gap-4 text-[10px] font-black uppercase tracking-wider">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-yellow-100 border border-yellow-300 rounded-sm"></span> Chờ duyệt</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-blue-100 border border-blue-300 rounded-sm"></span> Đã duyệt</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-100 border border-emerald-300 rounded-sm"></span> Hoàn tất</span>
        </div>
    </div>

</div>
@endsection
