<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">{{ $customer->name }}</h2>
                <p class="text-sm text-[#45464d]">{{ $customer->email ?? $customer->phone }}</p>
            </div>
            <a href="{{ route('owner.customers.index') }}" class="text-xs font-bold text-[#0f172a] underline">← Khách hàng</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-sm text-center">
                <p class="text-xs text-[#45464d] uppercase tracking-wider font-semibold">Tổng lượt đặt</p>
                <p class="text-2xl font-bold text-[#0f172a] mt-1">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-sm text-center">
                <p class="text-xs text-[#45464d] uppercase tracking-wider font-semibold">Tổng chi tiêu</p>
                <p class="text-2xl font-bold text-[#0f172a] mt-1">{{ number_format($stats['total_spent'], 0, ',', '.') }}đ</p>
            </div>
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-sm text-center">
                <p class="text-xs text-[#45464d] uppercase tracking-wider font-semibold">Lần cuối</p>
                <p class="text-lg font-bold text-[#0f172a] mt-1">{{ $stats['last_booking']?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
        </div>

        <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-3">Lịch Sử Đặt Lịch</h3>
        <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs uppercase tracking-wider text-[#45464d]">
                        <th class="text-left px-4 py-3 font-semibold">Sân</th>
                        <th class="text-left px-4 py-3 font-semibold">Ngày</th>
                        <th class="text-left px-4 py-3 font-semibold">Giờ</th>
                        <th class="text-right px-4 py-3 font-semibold">Giá</th>
                        <th class="text-center px-4 py-3 font-semibold">Trạng thái</th>
                        <th class="text-center px-4 py-3 font-semibold">Thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                    <tr class="border-t border-[#e2e8f0] hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $b->field?->name }}</td>
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
                        <td class="px-4 py-3 text-center text-xs">
                            @if($b->payment)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold uppercase
                                    @if($b->payment->status == 'paid') bg-green-100 text-green-800
                                    @elseif($b->payment->status == 'unpaid') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($b->payment->status == 'paid') Đã thanh toán
                                    @elseif($b->payment->status == 'unpaid') Chưa thanh toán
                                    @else {{ $b->payment->status }}
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($bookings->count() == 0)
                <p class="text-center py-8 text-sm text-[#45464d]">Chưa có lịch sử đặt lịch</p>
            @endif
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
