<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Hệ thống Quản trị
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Overview Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Tổng người dùng</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Khách hàng: {{ number_format($totalCustomers) }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Chủ sân (Owner)</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($totalOwners) }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Được phép quản trị bởi admin</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Đặt sân chờ xử lý</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($pendingBookings) }}</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h2m-4-3h9M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#45464d] font-bold">
                    <span>Quản lý theo luồng booking của hệ thống</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#45464d] uppercase tracking-wider">Doanh thu hệ thống</p>
                        <h3 class="text-3xl font-bold font-heading text-[#0f172a] mt-1">{{ number_format($totalRevenue, 0, ',', '.') }} đ</h3>
                    </div>
                    <div class="p-3 bg-slate-50 text-[#0f172a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 21h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#006d36] font-bold">
                    <span>Chỉ tính giao dịch đã thanh toán</span>
                </div>
            </div>
        </div>

        <!-- Main Workspace Content -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] transition-all">
            <div class="p-6 border-b border-[#e2e8f0] flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold font-heading text-[#0f172a] uppercase tracking-wide">Quản lý Hệ Thống Admin</h3>
                    <p class="text-xs text-[#45464d] mt-1">Danh sách hành động nhanh dành cho Quản trị viên hệ thống.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider rounded-lg transition-colors hover:bg-slate-800">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Quản lý tài khoản
                </a>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border border-[#e2e8f0] p-5 rounded-lg bg-[#f8fafc] hover:border-[#0f172a] transition-all">
                        <span class="p-2.5 bg-white shadow-sm border border-[#e2e8f0] rounded-lg inline-block mb-4 text-[#0f172a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </span>
                        <h4 class="font-bold text-sm text-[#0f172a] uppercase tracking-wider">Cài đặt Bảo mật</h4>
                        <p class="text-xs text-[#45464d] mt-2 leading-relaxed">Giám sát các nỗ lực đăng nhập sai, quản lý Token API hoạt động và kiểm duyệt lỗ hổng bảo mật hệ thống.</p>
                        <button class="mt-4 text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                            Cấu hình hệ thống <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div class="border border-[#e2e8f0] p-5 rounded-lg bg-[#f8fafc] hover:border-[#0f172a] transition-all">
                        <span class="p-2.5 bg-white shadow-sm border border-[#e2e8f0] rounded-lg inline-block mb-4 text-[#0f172a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </span>
                        <h4 class="font-bold text-sm text-[#0f172a] uppercase tracking-wider">Kiểm duyệt Cơ sở</h4>
                        <p class="text-xs text-[#45464d] mt-2 leading-relaxed">Xét duyệt yêu cầu mở bán sân đấu từ Chủ cơ sở (Owner), cập nhật danh mục bộ môn thể thao chính thức.</p>
                        <button class="mt-4 text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                            Danh sách kiểm duyệt <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div class="border border-[#e2e8f0] p-5 rounded-lg bg-[#f8fafc] hover:border-[#0f172a] transition-all">
                        <span class="p-2.5 bg-white shadow-sm border border-[#e2e8f0] rounded-lg inline-block mb-4 text-[#0f172a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </span>
                        <h4 class="font-bold text-sm text-[#0f172a] uppercase tracking-wider">Báo cáo Tổng hợp</h4>
                        <p class="text-xs text-[#45464d] mt-2 leading-relaxed">Phân tích biểu đồ phát triển người dùng, hiệu suất booking, báo cáo tài chính toàn diện hàng tháng.</p>
                        <button class="mt-4 text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                            Xem báo cáo <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
