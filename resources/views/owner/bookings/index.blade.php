<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Danh Sách Đặt Lịch</h2>
            <div class="flex gap-2">
                <a href="{{ route('owner.bookings.pending') }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-sm bg-white">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    Chờ duyệt
                </a>
                <a href="{{ route('owner.bookings.calendar') }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-sm bg-white">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Calendar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="bg-green-50 border border-[#4ade80]/40 text-[#0f172a] px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên khách, số điện thoại, sân..." class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div class="w-full sm:w-auto min-w-[140px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Trạng thái</label>
                    <select name="status" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto min-w-[150px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Sân</label>
                    <select name="field_id" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="">Tất cả sân</option>
                        @foreach($fields as $f)
                            <option value="{{ $f->id }}" {{ request('field_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto min-w-[130px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Từ ngày</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                </div>
                <div class="w-full sm:w-auto min-w-[130px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Đến ngày</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                </div>
                <button type="submit" class="px-5 py-2 bg-[#0f172a] text-[#4ade80] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">Lọc</button>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#e2e8f0] text-xs uppercase tracking-wider text-[#45464d]">
                            <th class="text-left px-5 py-3.5 font-bold">Khách hàng</th>
                            <th class="text-left px-5 py-3.5 font-bold">Sân</th>
                            <th class="text-left px-5 py-3.5 font-bold">Ngày chơi</th>
                            <th class="text-left px-5 py-3.5 font-bold">Khung giờ</th>
                            <th class="text-right px-5 py-3.5 font-bold">Doanh thu</th>
                            <th class="text-center px-5 py-3.5 font-bold">Trạng thái</th>
                            <th class="text-right px-5 py-3.5 font-bold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @foreach($bookings as $b)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#0f172a]">{{ $b->customer?->name ?? 'N/A' }}</div>
                                @if($b->customer?->phone)
                                    <div class="text-xs text-[#45464d] mt-0.5 flex items-center gap-0.5">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $b->customer->phone }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#0f172a]">{{ $b->field?->name }}</span>
                            </td>
                            <td class="px-5 py-4 text-[#45464d]">{{ $b->booking_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-[#0f172a] font-medium">{{ $b->timeSlot?->start_time?->format('H:i') }} - {{ $b->timeSlot?->end_time?->format('H:i') }}</td>
                            <td class="px-5 py-4 text-right font-bold text-[#0f172a]">{{ number_format($b->total_price, 0, ',', '.') }}đ</td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($b->status == 'confirmed') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($b->status == 'completed') bg-green-50 text-green-700 border border-green-200
                                    @elseif($b->status == 'cancelled') bg-red-50 text-red-700 border border-red-200
                                    @else bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @endif">
                                    @if($b->status == 'confirmed') Đã duyệt
                                    @elseif($b->status == 'completed') Hoàn tất
                                    @elseif($b->status == 'cancelled') Đã huỷ
                                    @else Chờ duyệt
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center gap-1.5 justify-end">
                                    @if($b->status == 'pending')
                                        <form action="{{ route('owner.bookings.confirm', $b) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg hover:bg-green-400 transition-colors shadow-sm" title="Duyệt đặt sân">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.bookings.cancel', $b) }}" method="POST" class="inline" onsubmit="return confirm('Huỷ đặt lịch này?')">
                                            @csrf
                                            <button class="p-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors border border-red-200" title="Từ chối/Huỷ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    @elseif($b->status == 'confirmed')
                                        <form action="{{ route('owner.bookings.checkin', $b) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận hoàn tất và chơi xong (check-in)?')">
                                            @csrf
                                            <button class="px-3 py-1.5 text-xs font-bold bg-[#0f172a] text-[#4ade80] rounded-lg hover:bg-slate-800 transition-all shadow-sm">Hoàn thành</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($bookings->count() == 0)
                <div class="text-center py-10 text-sm text-[#45464d]">Không tìm thấy đặt lịch nào phù hợp</div>
            @endif
        </div>
        <div class="mt-5">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
