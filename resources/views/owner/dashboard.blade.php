<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Owner Dashboard') }}
            </h2>
            <span class="bg-[#4ade80] text-[#0f172a] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Quản Lý Chủ Sân
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Yard Owner Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Sân đang hoạt động</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">6 / 8</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Đạt hiệu suất 75% hôm nay</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Đặt lịch hôm nay</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">24</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span>+4 lượt đặt mới mới</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Doanh thu hôm nay</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">4.8M</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 21h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>90% đã thanh toán online</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Đánh giá trung bình</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">4.9 / 5</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.969 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.178 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.49 10.11c-.783-.57-.38-1.81.588-1.81h4.906a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#45464d] font-bold">
                    <span>Dựa trên 142 lượt review</span>
                </div>
            </div>
        </div>

        <!-- Booking & Court Management Workspace -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide">Yêu Cầu Đặt Lịch Chờ Duyệt</h3>
                        <p class="text-xs text-[#45464d] mt-1">Xem và duyệt các lượt đặt sân bóng đá, tennis đang chờ xử lý.</p>
                    </div>
                </div>

                <div class="divide-y divide-[#e2e8f0]">
                    <!-- Booking Row 1 -->
                    <div class="py-4 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-[#f8fafc] text-xs font-bold text-[#0f172a] flex items-center justify-center border border-[#e2e8f0]">
                                KH
                            </span>
                            <div>
                                <h4 class="font-bold text-sm text-[#0f172a]">Trần Minh Khang</h4>
                                <p class="text-xs text-[#45464d] mt-0.5">Sân bóng đá 7 người | Sân Số 2</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <span class="block text-sm font-semibold text-[#0f172a]">17:30 - 19:00</span>
                                <span class="block text-xs text-[#45464d]">Hôm nay, 19/05</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg transition-transform active:scale-90 shadow-sm" title="Duyệt">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                <button class="p-1.5 bg-red-100 text-red-700 rounded-lg transition-transform active:scale-90" title="Từ chối">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Row 2 -->
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-[#f8fafc] text-xs font-bold text-[#0f172a] flex items-center justify-center border border-[#e2e8f0]">
                                NV
                            </span>
                            <div>
                                <h4 class="font-bold text-sm text-[#0f172a]">Nguyễn Văn Nam</h4>
                                <p class="text-xs text-[#45464d] mt-0.5">Sân Tennis VIP | Sân Đất Nện</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <span class="block text-sm font-semibold text-[#0f172a]">20:00 - 22:00</span>
                                <span class="block text-xs text-[#45464d]">Hôm nay, 19/05</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 bg-[#4ade80] text-[#0f172a] rounded-lg transition-transform active:scale-90 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                <button class="p-1.5 bg-red-100 text-red-700 rounded-lg transition-transform active:scale-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Quick Control -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide mb-4">Thao Tác Nhanh</h3>
                    
                    <button class="w-full mb-3 flex items-center justify-between p-3.5 bg-[#0f172a] text-[#4ade80] rounded-xl font-bold uppercase tracking-wider text-xs shadow-sm hover:bg-slate-800 transition-colors">
                        <span>Đăng ký thêm sân đấu mới</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </button>

                    <button class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                        <span>Cài đặt giờ hoạt động & Giá</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </button>
                </div>

                <div class="pt-6 border-t border-[#e2e8f0]">
                    <div class="flex items-center gap-3 text-xs bg-slate-50 border border-[#e2e8f0] p-3 rounded-lg text-[#45464d]">
                        <svg class="w-5 h-5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Cơ sở của bạn đã được kiểm duyệt hợp lệ từ hệ thống PlayManagement.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
