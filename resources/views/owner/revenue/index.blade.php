<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Báo Cáo Doanh Thu</h2>
    </x-slot>
    <div class="py-6">
        <!-- Date Filter -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="text-xs font-semibold text-[#45464d] block mb-1">Từ ngày</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] block mb-1">Đến ngày</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0f172a] text-white rounded-lg text-xs font-bold uppercase tracking-wider">Xem</button>
                <a href="{{ route('owner.revenue.index', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 1]) }}" class="px-4 py-2 border border-[#0f172a] text-[#0f172a] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50">Export CSV</a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tổng doanh thu</p>
                <p class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
            </div>
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tổng đặt lịch</p>
                <p class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $totalBookings }}</p>
            </div>
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">TB / booking</p>
                <p class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ $totalBookings > 0 ? number_format($totalRevenue / $totalBookings, 0, ',', '.') : 0 }}đ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- By Date -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Theo Ngày</h3>
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-[#e2e8f0] text-xs uppercase text-[#45464d]"><th class="text-left py-2 font-semibold">Ngày</th><th class="text-right py-2 font-semibold">SL</th><th class="text-right py-2 font-semibold">Doanh thu</th></tr></thead>
                    <tbody>
                        @foreach($daily as $d)
                        <tr class="border-b border-[#e2e8f0] hover:bg-slate-50">
                            <td class="py-2">{{ $d->booking_date->format('d/m/Y') }}</td>
                            <td class="py-2 text-right">{{ $d->count }}</td>
                            <td class="py-2 text-right font-semibold">{{ number_format($d->revenue, 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- By Payment Method -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Theo Phương Thức Thanh Toán</h3>
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-[#e2e8f0] text-xs uppercase text-[#45464d]"><th class="text-left py-2 font-semibold">Phương thức</th><th class="text-right py-2 font-semibold">SL</th><th class="text-right py-2 font-semibold">Doanh thu</th></tr></thead>
                    <tbody>
                        @foreach($byMethod as $m)
                        <tr class="border-b border-[#e2e8f0] hover:bg-slate-50">
                            <td class="py-2 capitalize">{{ $m->method ?? 'N/A' }}</td>
                            <td class="py-2 text-right">{{ $m->count }}</td>
                            <td class="py-2 text-right font-semibold">{{ number_format($m->revenue, 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                        @if($byMethod->count() == 0)
                            <tr><td colspan="3" class="py-4 text-center text-sm text-[#45464d]">Chưa có dữ liệu</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- By Field -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm lg:col-span-2">
                <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Theo Sân</h3>
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-[#e2e8f0] text-xs uppercase text-[#45464d]"><th class="text-left py-2 font-semibold">Sân</th><th class="text-right py-2 font-semibold">SL</th><th class="text-right py-2 font-semibold">Doanh thu</th></tr></thead>
                    <tbody>
                        @foreach($byField as $f)
                        <tr class="border-b border-[#e2e8f0] hover:bg-slate-50">
                            <td class="py-2">{{ $f['field_name'] }}</td>
                            <td class="py-2 text-right">{{ $f['bookings'] }}</td>
                            <td class="py-2 text-right font-semibold">{{ number_format($f['revenue'], 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
