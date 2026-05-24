<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Đặt Lịch</h2>
            <div class="flex gap-2">
                <a href="{{ route('owner.bookings.pending') }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50">Chờ duyệt</a>
                <a href="{{ route('owner.bookings.calendar') }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50">Calendar</a>
                <a href="{{ route('owner.bookings.index', ['status' => 'pending']) }}" class="px-3 py-2 bg-[#0f172a] text-[#4ade80] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800">+ Lọc</a>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên khách, sân..." class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <select name="status" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                    </select>
                </div>
                <div>
                    <select name="field_id" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="">Tất cả sân</option>
                        @foreach($fields as $f)
                            <option value="{{ $f->id }}" {{ request('field_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm"></div>
                <div><input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm"></div>
                <button type="submit" class="px-4 py-2 bg-[#0f172a] text-white rounded-lg text-xs font-bold uppercase tracking-wider">Lọc</button>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs uppercase tracking-wider text-[#45464d]">
                        <th class="text-left px-4 py-3 font-semibold">Khách hàng</th>
                        <th class="text-left px-4 py-3 font-semibold">Sân</th>
                        <th class="text-left px-4 py-3 font-semibold">Ngày</th>
                        <th class="text-left px-4 py-3 font-semibold">Giờ</th>
                        <th class="text-right px-4 py-3 font-semibold">Giá</th>
                        <th class="text-center px-4 py-3 font-semibold">TT</th>
                        <th class="text-right px-4 py-3 font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                    <tr class="border-t border-[#e2e8f0] hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <span class="font-semibold text-[#0f172a]">{{ $b->customer?->name ?? 'N/A' }}</span>
                            @if($b->customer?->phone)<br><span class="text-xs text-[#45464d]">{{ $b->customer->phone }}</span>@endif
                        </td>
                        <td class="px-4 py-3 text-[#0f172a]">{{ $b->field?->name }}</td>
                        <td class="px-4 py-3">{{ $b->booking_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $b->timeSlot?->start_time?->format('H:i') }} - {{ $b->timeSlot?->end_time?->format('H:i') }}</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($b->total_price, 0, ',', '.') }}đ</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase
                                @if($b->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($b->status == 'completed') bg-[#4ade80] text-[#0f172a]
                                @elseif($b->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                @if($b->status == 'confirmed') Đã duyệt
                                @elseif($b->status == 'completed') Hoàn tất
                                @elseif($b->status == 'cancelled') Đã huỷ
                                @else Chờ duyệt
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center gap-1 justify-end">
                                @if($b->status == 'pending')
                                    <form action="{{ route('owner.bookings.confirm', $b) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg hover:bg-green-400" title="Duyệt"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></button>
                                    </form>
                                    <form action="{{ route('owner.bookings.cancel', $b) }}" method="POST" class="inline" onsubmit="return confirm('Huỷ đặt lịch này?')">
                                        @csrf
                                        <button class="p-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200" title="Từ chối"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </form>
                                @elseif($b->status == 'confirmed')
                                    <form action="{{ route('owner.bookings.checkin', $b) }}" method="POST" class="inline" onsubmit="return confirm('Check-in khách?')">
                                        @csrf
                                        <button class="px-2 py-1 text-xs font-bold bg-[#0f172a] text-[#4ade80] rounded-lg hover:bg-slate-800">Check-in</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($bookings->count() == 0)
                <p class="text-center py-8 text-sm text-[#45464d]">Không có đặt lịch nào</p>
            @endif
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
