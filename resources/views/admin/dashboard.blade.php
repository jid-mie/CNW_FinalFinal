<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold font-heading text-2xl text-slate-900 uppercase tracking-wider">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="bg-slate-900 text-[#3cd882] text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full shadow-lg border border-slate-800">
                Hệ thống Quản trị
            </span>
        </div>
    </x-slot>

    <div class="p-6 bg-[#f8fafc] min-h-screen">
        <!-- Overview Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Users -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 p-6 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng người dùng</p>
                        <h3 class="text-3xl font-extrabold font-heading text-slate-900 mt-2">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.342 9.342 0 0 1 12.244 21c-1.11 0-2.167-.194-3.15-.547m12.444-1.325a12.02 12.02 0 0 0 .193-1.17c.028-.29.041-.583.041-.878 0-2.03-1.002-3.83-2.533-4.94M15 19.128a9.38 9.38 0 0 1-2.625.372 9.337 9.337 0 0 1-4.121-.952 4.125 4.125 0 0 1 7.533-2.493M9 19.128v-.003c0-1.113.285-2.16.786-3.07M9 19.128v.109A9.342 9.342 0 0 0 12.244 21c1.11 0 2.167-.194 3.15-.547m-12.444-1.325a12.02 12.02 0 0 1-.193-1.17c-.028-.29-.041-.583-.041-.878 0-2.03 1.002-3.83 2.533-4.94M10.5 8.12a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM6.75 8.12a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0Z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-slate-800 font-semibold">
                    <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-650">Khách hàng: {{ number_format($totalCustomers) }}</span>
                </div>
            </div>

            <!-- Card 2: Owners -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 p-6 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Chủ sân (Owner)</p>
                        <h3 class="text-3xl font-extrabold font-heading text-slate-900 mt-2">{{ number_format($totalOwners) }}</h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3.5v18m3.5-13.636 10.5-3.82"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-slate-800 font-semibold">
                    <span class="bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded">Được phép quản trị bởi admin</span>
                </div>
            </div>

            <!-- Card 3: Pending Bookings -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 p-6 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Đặt sân chờ xử lý</p>
                        <h3 class="text-3xl font-extrabold font-heading text-slate-900 mt-2">{{ number_format($pendingBookings) }}</h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-slate-800 font-semibold">
                    <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-650">Quản lý theo luồng booking</span>
                </div>
            </div>

            <!-- Card 4: Revenue -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 p-6 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Doanh thu hệ thống</p>
                        <h3 class="text-3xl font-extrabold font-heading text-slate-900 mt-2">{{ number_format($totalRevenue, 0, ',', '.') }} đ</h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-50 text-[#047857] rounded-xl border border-slate-100 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-[#047857] font-extrabold">
                    <span class="bg-emerald-50 px-2.5 py-0.5 rounded shadow-sm">Chỉ tính giao dịch thành công</span>
                </div>
            </div>
        </div>

        <!-- 📊 Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Line Chart: Monthly Revenue (2/3 width) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"></path></svg>
                    Doanh thu 6 tháng gần nhất (VND)
                </h3>
                <div class="relative w-full" style="height: 260px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Bookings by Sport (1/3 width) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path></svg>
                    Phân bổ Đặt sân theo Bộ môn
                </h3>
                <div class="relative w-full flex justify-center" style="height: 260px;">
                    <canvas id="sportsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Main Workspace Content -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200 transition-all">
            <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-extrabold font-heading text-slate-900 uppercase tracking-wider">Quản lý Hệ Thống Admin</h3>
                    <p class="text-xs text-slate-500 mt-1 font-semibold">Danh sách hành động nhanh dành cho Quản trị viên hệ thống.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-[#3cd882] text-xs font-bold uppercase tracking-wider rounded-xl transition-all duration-200 hover:bg-slate-800 shadow-md">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.342 9.342 0 0 1 12.244 21c-1.11 0-2.167-.194-3.15-.547m12.444-1.325a12.02 12.02 0 0 0 .193-1.17c.028-.29.041-.583.041-.878 0-2.03-1.002-3.83-2.533-4.94M15 19.128a9.38 9.38 0 0 1-2.625.372 9.337 9.337 0 0 1-4.121-.952 4.125 4.125 0 0 1 7.533-2.493M9 19.128v-.003c0-1.113.285-2.16.786-3.07M9 19.128v.109A9.342 9.342 0 0 0 12.244 21c1.11 0 2.167-.194 3.15-.547m-12.444-1.325a12.02 12.02 0 0 1-.193-1.17c-.028-.29-.041-.583-.041-.878 0-2.03 1.002-3.83 2.533-4.94M10.5 8.12a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM6.75 8.12a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0Z"></path></svg>
                    Quản lý tài khoản
                </a>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Action 1 -->
                    <div class="border border-slate-200 p-6 rounded-2xl bg-slate-50 hover:border-slate-400 hover:bg-white transition-all duration-350 flex flex-col justify-between group shadow-sm hover:shadow-md">
                        <div>
                            <div class="w-12 h-12 flex items-center justify-center bg-white shadow-sm border border-slate-200 rounded-xl mb-4 text-slate-900 group-hover:bg-[#3cd882]/10 group-hover:text-[#047857] group-hover:border-[#3cd882]/20 transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Cài đặt Bảo mật</h4>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed font-semibold">Giám sát các nỗ lực đăng nhập sai, quản lý Token API hoạt động và kiểm duyệt lỗ hổng bảo mật hệ thống.</p>
                        </div>
                        <a href="{{ route('admin.security.index') }}" class="mt-6 text-xs font-bold text-slate-900 group-hover:text-[#047857] flex items-center gap-1.5 transition-colors">
                            Cấu hình hệ thống <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                        </a>
                    </div>

                    <!-- Action 2 -->
                    <div class="border border-slate-200 p-6 rounded-2xl bg-slate-50 hover:border-slate-400 hover:bg-white transition-all duration-355 flex flex-col justify-between group shadow-sm hover:shadow-md">
                        <div>
                            <div class="w-12 h-12 flex items-center justify-center bg-white shadow-sm border border-slate-200 rounded-xl mb-4 text-slate-900 group-hover:bg-[#3cd882]/10 group-hover:text-[#047857] group-hover:border-[#3cd882]/20 transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 16.5h1.5m3 0H15"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Kiểm duyệt Cơ sở</h4>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed font-semibold">Xét duyệt yêu cầu mở bán sân đấu từ Chủ cơ sở (Owner), cập nhật danh mục bộ môn thể thao chính thức.</p>
                        </div>
                        <a href="{{ route('admin.fields.index') }}" class="mt-6 text-xs font-bold text-slate-900 group-hover:text-[#047857] flex items-center gap-1.5 transition-colors">
                            Danh sách kiểm duyệt <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                        </a>
                    </div>

                    <!-- Action 3 -->
                    <div class="border border-slate-200 p-6 rounded-2xl bg-slate-50 hover:border-slate-400 hover:bg-white transition-all duration-360 flex flex-col justify-between group shadow-sm hover:shadow-md">
                        <div>
                            <div class="w-12 h-12 flex items-center justify-center bg-white shadow-sm border border-slate-200 rounded-xl mb-4 text-slate-900 group-hover:bg-[#3cd882]/10 group-hover:text-[#047857] group-hover:border-[#3cd882]/20 transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Báo cáo Tổng hợp</h4>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed font-semibold">Phân tích biểu đồ phát triển người dùng, hiệu suất booking, báo cáo tài chính toàn diện hàng tháng.</p>
                        </div>
                        <a href="{{ route('admin.payments.index') }}" class="mt-6 text-xs font-bold text-slate-900 group-hover:text-[#047857] flex items-center gap-1.5 transition-colors">
                            Xem báo cáo <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script CDN & Config -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 📊 Chart 1: Doanh thu 6 tháng gần nhất
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            
            // Create nice gradient for revenue area
            const revGradient = revCtx.createLinearGradient(0, 0, 0, 260);
            revGradient.addColorStop(0, 'rgba(60, 216, 130, 0.35)');
            revGradient.addColorStop(1, 'rgba(60, 216, 130, 0.00)');

            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueLabels) !!},
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: {!! json_encode($revenueData) !!},
                        borderColor: '#3cd882',
                        borderWidth: 2.5,
                        backgroundColor: revGradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1e2538',
                        pointBorderColor: '#3cd882',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            border: {
                                dash: [5, 5]
                            },
                            ticks: {
                                color: '#475569',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'M';
                                    if (value >= 1000) return (value / 1000) + 'K';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#475569',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // ⚽ Chart 2: Phân bổ Đặt sân theo Bộ môn
            const sportsCtx = document.getElementById('sportsChart').getContext('2d');
            new Chart(sportsCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($sportLabels) !!},
                    datasets: [{
                        data: {!! json_encode($sportData) !!},
                        backgroundColor: [
                            '#0f172a',
                            '#3cd882',
                            '#38bdf8',
                            '#fb7185',
                            '#f59e0b',
                            '#10b981',
                            '#6366f1',
                            '#ec4899'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 15,
                                color: '#334155',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        });
    </script>
</x-app-layout>
