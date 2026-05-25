<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Báo Cáo Doanh Thu</h2>
    </x-slot>
    
    <div class="py-6">
        <!-- Date Filter & Actions -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-4 items-end justify-between">
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Từ ngày</label>
                        <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Đến ngày</label>
                        <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                    </div>
                    <button type="submit" class="px-5 py-2 bg-[#0f172a] text-[#4ade80] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">
                        Lọc báo cáo
                    </button>
                </div>
                
                <div>
                    <a href="{{ route('owner.revenue.index', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 1]) }}" class="px-4 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors flex items-center gap-1.5 bg-white shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Xuất file CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div class="bg-[#0f172a] rounded-xl p-6 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 text-slate-800 opacity-20 transform group-hover:scale-110 transition-transform">
                    <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/></svg>
                </div>
                <p class="text-[10px] font-bold text-[#4ade80] uppercase tracking-wider">Tổng doanh thu thực nhận</p>
                <p class="text-3xl font-bold font-heading text-white mt-1.5">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
            </div>
            
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <p class="text-[10px] font-bold text-[#45464d] uppercase tracking-wider">Tổng lượt đặt lịch</p>
                <p class="text-3xl font-bold font-heading text-[#0f172a] mt-1.5">{{ $totalBookings }}</p>
            </div>
            
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
                <p class="text-[10px] font-bold text-[#45464d] uppercase tracking-wider">Giá trị trung bình</p>
                <p class="text-3xl font-bold font-heading text-[#0f172a] mt-1.5">{{ $totalBookings > 0 ? number_format($totalRevenue / $totalBookings, 0, ',', '.') : 0 }}đ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- By Date -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 mb-4">
                    <h3 class="font-bold text-xs text-[#0f172a] uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                        Theo Ngày
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-[#e2e8f0] text-[10px] uppercase tracking-wider text-[#45464d]">
                                <th class="text-left px-3 py-2.5 font-bold">Ngày</th>
                                <th class="text-right px-3 py-2.5 font-bold">Số lượt</th>
                                <th class="text-right px-3 py-2.5 font-bold">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @foreach($daily as $d)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-2.5 text-[#0f172a] font-medium">{{ $d->booking_date->format('d/m/Y') }}</td>
                                <td class="px-3 py-2.5 text-right font-medium text-[#45464d]">{{ $d->count }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#0f172a]">{{ number_format($d->revenue, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                            @if(count($daily) == 0)
                                <tr><td colspan="3" class="px-3 py-6 text-center text-xs text-[#45464d]">Chưa có dữ liệu</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- By Payment Method -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 mb-4">
                    <h3 class="font-bold text-xs text-[#0f172a] uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                        Theo Phương Thức
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-[#e2e8f0] text-[10px] uppercase tracking-wider text-[#45464d]">
                                <th class="text-left px-3 py-2.5 font-bold">Phương thức</th>
                                <th class="text-right px-3 py-2.5 font-bold">Số lượt</th>
                                <th class="text-right px-3 py-2.5 font-bold">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @foreach($byMethod as $m)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-2.5 text-[#0f172a] font-medium capitalize">{{ $m->method ?? 'Không xác định' }}</td>
                                <td class="px-3 py-2.5 text-right font-medium text-[#45464d]">{{ $m->count }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#0f172a]">{{ number_format($m->revenue, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                            @if($byMethod->count() == 0)
                                <tr><td colspan="3" class="px-3 py-6 text-center text-xs text-[#45464d]">Chưa có dữ liệu</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- By Field -->
            <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 mb-4">
                    <h3 class="font-bold text-xs text-[#0f172a] uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                        Theo Sân Đấu
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-[#e2e8f0] text-[10px] uppercase tracking-wider text-[#45464d]">
                                <th class="text-left px-3 py-2.5 font-bold">Sân đấu</th>
                                <th class="text-right px-3 py-2.5 font-bold">Số lượt đặt</th>
                                <th class="text-right px-3 py-2.5 font-bold">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @foreach($byField as $f)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-2.5 text-[#0f172a] font-bold">{{ $f['field_name'] }}</td>
                                <td class="px-3 py-2.5 text-right font-medium text-[#45464d]">{{ $f['bookings'] }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#0f172a]">{{ number_format($f['revenue'], 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                            @if(count($byField) == 0)
                                <tr><td colspan="3" class="px-3 py-6 text-center text-xs text-[#45464d]">Chưa có dữ liệu</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
