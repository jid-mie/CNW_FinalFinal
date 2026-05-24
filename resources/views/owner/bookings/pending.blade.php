<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Chờ Duyệt</h2>
            <a href="{{ route('owner.bookings.index') }}" class="text-xs font-bold text-[#0f172a] underline">← Tất cả</a>
        </div>
    </x-slot>
    <div class="py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs uppercase tracking-wider text-[#45464d]">
                        <th class="text-left px-4 py-3 font-semibold">Khách hàng</th>
                        <th class="text-left px-4 py-3 font-semibold">SĐT</th>
                        <th class="text-left px-4 py-3 font-semibold">Sân</th>
                        <th class="text-left px-4 py-3 font-semibold">Ngày</th>
                        <th class="text-left px-4 py-3 font-semibold">Giờ</th>
                        <th class="text-right px-4 py-3 font-semibold">Giá</th>
                        <th class="text-right px-4 py-3 font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                    <tr class="border-t border-[#e2e8f0] hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-[#0f172a]">{{ $b->customer?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-[#45464d]">{{ $b->customer?->phone ?? '' }}</td>
                        <td class="px-4 py-3">{{ $b->field?->name }}</td>
                        <td class="px-4 py-3">{{ $b->booking_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $b->timeSlot?->start_time?->format('H:i') }} - {{ $b->timeSlot?->end_time?->format('H:i') }}</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($b->total_price, 0, ',', '.') }}đ</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center gap-1 justify-end">
                                <form action="{{ route('owner.bookings.confirm', $b) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-green-400">Duyệt</button>
                                </form>
                                <form action="{{ route('owner.bookings.cancel', $b) }}" method="POST" class="inline" onsubmit="var r=confirm('Huỷ?'); if(r){var n=prompt('Lý do từ chối (không bắt buộc):'); this.querySelector('input[name=note]').value=n||'';} return r;">
                                    @csrf
                                    <input type="hidden" name="note" value="">
                                    <button class="px-3 py-1.5 border border-red-200 text-red-700 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-red-50">Từ chối</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($bookings->count() == 0)
                <p class="text-center py-8 text-sm text-[#45464d]">Không có yêu cầu chờ duyệt</p>
            @endif
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
