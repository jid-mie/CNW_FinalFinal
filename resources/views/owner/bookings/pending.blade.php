<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Yêu Cầu Chờ Duyệt</h2>
            <a href="{{ route('owner.bookings.index') }}" class="text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Xem tất cả đặt lịch
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="bg-green-50 border border-[#4ade80]/40 text-[#0f172a] px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#e2e8f0] text-xs uppercase tracking-wider text-[#45464d]">
                            <th class="text-left px-5 py-3.5 font-bold">Khách hàng</th>
                            <th class="text-left px-5 py-3.5 font-bold">Số điện thoại</th>
                            <th class="text-left px-5 py-3.5 font-bold">Sân đấu</th>
                            <th class="text-left px-5 py-3.5 font-bold">Ngày chơi</th>
                            <th class="text-left px-5 py-3.5 font-bold">Khung giờ</th>
                            <th class="text-right px-5 py-3.5 font-bold">Doanh thu</th>
                            <th class="text-right px-5 py-3.5 font-bold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @foreach($bookings as $b)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-bold text-[#0f172a]">{{ $b->customer?->name ?? 'N/A' }}</td>
                            <td class="px-5 py-4 text-[#45464d] font-medium">{{ $b->customer?->phone ?? '—' }}</td>
                            <td class="px-5 py-4 font-semibold text-[#0f172a]">{{ $b->field?->name }}</td>
                            <td class="px-5 py-4 text-[#45464d]">{{ $b->booking_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-[#0f172a] font-medium">{{ $b->timeSlot?->start_time?->format('H:i') }} - {{ $b->timeSlot?->end_time?->format('H:i') }}</td>
                            <td class="px-5 py-4 text-right font-bold text-[#0f172a]">{{ number_format($b->total_price, 0, ',', '.') }}đ</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <form action="{{ route('owner.bookings.confirm', $b) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="px-3.5 py-2 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">
                                            Duyệt đặt
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.bookings.cancel', $b) }}" method="POST" class="inline" onsubmit="var r=confirm('Từ chối yêu cầu đặt sân này?'); if(r){var n=prompt('Lý do từ chối (không bắt buộc):'); this.querySelector('input[name=note]').value=n||'';} return r;">
                                        @csrf
                                        <input type="hidden" name="note" value="">
                                        <button class="px-3.5 py-2 border border-red-200 text-red-700 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-red-50 transition-colors">
                                            Từ chối
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($bookings->count() == 0)
                <div class="text-center py-12 text-sm text-[#45464d]">Không có yêu cầu đặt lịch nào đang chờ duyệt</div>
            @endif
        </div>
        <div class="mt-5">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
