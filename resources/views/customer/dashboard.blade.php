<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('My Dashboard') }}
            </h2>
            <span class="bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Thành Viên
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Customer Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Số trận sắp đá</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">2</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a] font-semibold">
                    <span>Trận tiếp theo: 18:00 Ngày mai</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Trận đã hoàn thành</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">18</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Hoàn thành 100% lịch đặt</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tài khoản Play Wallet</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">350,000đ</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#0f172a] hover:underline cursor-pointer font-bold">
                    <span>Nạp thêm tiền vào ví</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Điểm tích lũy (Stars)</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">1,420</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#45464d] font-bold">
                    <span>Hạng Bạc (Silver Member)</span>
                </div>
            </div>
        </div>

        <!-- Bookings & Arena Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: List of active bookings -->
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide">Lịch Đặt Sân Của Tôi</h3>
                        <p class="text-xs text-[#45464d] mt-1">Xem trạng thái chi tiết lịch hẹn bóng đá, cầu lông sắp tới.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Ticket 1 -->
                    <div class="border border-[#e2e8f0] rounded-xl p-4 bg-[#f8fafc] flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="bg-[#e2e8f0] text-[#0f172a] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">BÓNG ĐÁ</span>
                                <span class="bg-[#dcfce7] text-[#15803d] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">ĐÃ XÁC NHẬN</span>
                            </div>
                            <h4 class="font-bold text-base text-[#0f172a] mt-2">Sân Bóng Đá Tuyên Sơn - Sân Số 5B</h4>
                            <p class="text-xs text-[#45464d] mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                357 Phan Châu Trinh, Hải Châu, Đà Nẵng
                            </p>
                        </div>
                        <div class="flex items-center justify-between md:flex-col md:items-end gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-[#e2e8f0]">
                            <div class="text-left md:text-right">
                                <span class="block text-sm font-bold text-[#0f172a]">18:00 - 19:30</span>
                                <span class="block text-xs text-[#45464d]">Ngày mai, 20/05</span>
                            </div>
                            <span class="text-xs font-bold text-[#0f172a] bg-white border border-[#e2e8f0] px-3 py-1.5 rounded-lg shadow-sm">
                                Xem mã vé
                            </span>
                        </div>
                    </div>

                    <!-- Ticket 2 -->
                    <div class="border border-[#e2e8f0] rounded-xl p-4 bg-[#f8fafc] flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="bg-[#e2e8f0] text-[#0f172a] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">CẦU LÔNG</span>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">CHỜ DUYỆT</span>
                            </div>
                            <h4 class="font-bold text-base text-[#0f172a] mt-2">Nhà Thi Đấu Thể Thao Hòa Xuân - Thảm Số 2</h4>
                            <p class="text-xs text-[#45464d] mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                02 Nguyễn Mậu Tài, Cẩm Lệ, Đà Nẵng
                            </p>
                        </div>
                        <div class="flex items-center justify-between md:flex-col md:items-end gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-[#e2e8f0]">
                            <div class="text-left md:text-right">
                                <span class="block text-sm font-bold text-[#0f172a]">08:00 - 10:00</span>
                                <span class="block text-xs text-[#45464d]">Chủ Nhật, 24/05</span>
                            </div>
                            <span class="text-xs font-semibold text-red-600 cursor-pointer hover:underline">
                                Hủy đặt lịch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Call To Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-bold font-heading text-[#0f172a] uppercase tracking-wide mb-4">Hoạt Động</h3>
                    
                    <button class="w-full mb-3 flex items-center justify-between p-3.5 bg-[#0f172a] text-[#4ade80] rounded-xl font-bold uppercase tracking-wider text-xs shadow-sm hover:bg-slate-800 transition-colors">
                        <span>Đặt Sân Đấu Ngay</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <button class="w-full mb-3 flex items-center justify-between p-3.5 border border-[#0f172a] text-[#0f172a] rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-[#f8fafc] transition-colors">
                        <span>Lịch sử đặt sân</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>

                <div class="pt-6 border-t border-[#e2e8f0]">
                    <p class="text-xs text-[#45464d] leading-relaxed">
                        Bạn muốn tìm thêm đồng đội đá giao hữu? Hãy tham gia ngay các group thể thao kết nối thành viên PlayManagement!
                    </p>
                    <a href="#" class="inline-block mt-3 text-xs font-bold text-[#0f172a] hover:underline">Tìm nhóm giao lưu thể thao &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
