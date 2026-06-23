<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PlayManagement - Hệ Thống Đặt Sân & Quản Lý Sân Thể Thao Tự Động</title>
        <meta name="description" content="Đặt lịch sân bóng đá, cầu lông, tennis trực tuyến nhanh chóng trong 30 giây. Hỗ trợ thanh toán tự động VietQR và quản lý vận hành chuyên nghiệp qua Seepay.">

        <!-- Google Fonts: Archivo Narrow & Hanken Grotesk -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Vite Assets (Tailwind & Alpine) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom CSS adjustments to enforce the strict design guidelines */
            body {
                font-family: 'Hanken Grotesk', sans-serif;
                background-color: #0B0F17;
            }
            h1, h2, h3, h4, h5, h6, .font-heading {
                font-family: 'Archivo Narrow', sans-serif;
            }
            /* Spotlight floodlight background gradient simulation */
            .hero-glow {
                background: radial-gradient(circle at 50% -20%, rgba(57, 255, 20, 0.15) 0%, transparent 60%);
            }
            .orange-glow {
                background: radial-gradient(circle at 100% 50%, rgba(255, 87, 34, 0.08) 0%, transparent 50%);
            }
            /* Grid overlay effect representing stadium net patterns */
            .grid-pattern {
                background-size: 40px 40px;
                background-image: 
                    linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            }
            /* Neon pulse effect */
            @keyframes neon-pulse {
                0%, 100% { opacity: 0.4; }
                50% { opacity: 1; }
            }
            .pulse-neon {
                animation: neon-pulse 2s infinite;
            }
        </style>
    </head>
    <body x-data="{ showPartnerModal: false }" class="text-gray-100 antialiased min-h-screen relative overflow-x-hidden selection:bg-[#39FF14] selection:text-black">
        
        <!-- Background elements -->
        <div class="absolute inset-0 grid-pattern pointer-events-none z-0"></div>
        <div class="absolute top-0 left-0 w-full h-[800px] hero-glow pointer-events-none z-0"></div>
        <div class="absolute top-[800px] right-0 w-[500px] h-[500px] orange-glow pointer-events-none z-0"></div>

        <!-- 1. FLOATING GLASSMORPHIC NAVIGATION HEADER -->
        <header class="fixed top-0 left-0 w-full z-50 bg-[#0B0F17]/80 backdrop-blur-md border-b border-[#39FF14]/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <!-- Logo brand -->
                <a href="/" class="flex items-center gap-2 group" id="nav-brand-logo">
                    <span class="w-8 h-8 bg-[#39FF14] text-black font-heading font-extrabold flex items-center justify-center text-xl rounded-[2px] transform transition-transform group-hover:scale-105">
                        P
                    </span>
                    <span class="font-heading font-extrabold text-2xl tracking-wider text-white group-hover:text-[#39FF14] transition-colors">
                        PLAY<span class="text-[#39FF14]">MANAGEMENT</span>
                    </span>
                </a>

                <!-- Navigation menu -->
                <nav class="hidden md:flex items-center gap-8 font-heading text-lg uppercase tracking-wide">
                    <a href="#demo" class="text-gray-300 hover:text-[#39FF14] transition-colors" id="nav-link-demo">Đặt Sân Thử</a>
                    <a href="#features" class="text-gray-300 hover:text-[#39FF14] transition-colors" id="nav-link-features">Tính Năng</a>
                    <a href="#testimonials" class="text-gray-300 hover:text-[#39FF14] transition-colors" id="nav-link-testimonials">Ý Kiến Khách Hàng</a>
                    <a href="#" @click.prevent="showPartnerModal = true" class="text-gray-300 hover:text-[#FF5722] transition-colors" id="nav-link-register-court">Đăng Ký Chủ Sân</a>
                </nav>

                <!-- Auth action buttons -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="px-5 py-2.5 bg-[#39FF14] text-black font-heading font-bold uppercase tracking-wide rounded-[2px] transition-all hover:bg-[#39FF14]/90 hover:shadow-[0_0_15px_rgba(57,255,20,0.4)] text-sm"
                               id="btn-nav-dashboard">
                                Đi đến Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-4 py-2 text-gray-300 font-heading uppercase tracking-wide hover:text-white transition-colors text-sm"
                               id="btn-nav-login">
                                Đăng Nhập
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-5 py-2.5 bg-transparent border border-[#39FF14] text-[#39FF14] font-heading font-bold uppercase tracking-wide rounded-[2px] transition-all hover:bg-[#39FF14] hover:text-black text-sm"
                                   id="btn-nav-register">
                                    Đăng Ký
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

            </div>
        </header>

        <main class="relative z-10 pt-20">

            <!-- 2. HERO SECTION -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 md:pt-28 md:pb-32 text-center">
                
                <!-- Floodlight visual indicator -->
                <div class="flex justify-center mb-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-[#161F30] border border-[#39FF14]/30 rounded-[2px] text-xs font-semibold tracking-wider text-[#39FF14] uppercase">
                        <span class="w-2 h-2 rounded-none bg-[#39FF14] pulse-neon"></span>
                        Hệ thống tự động hóa thể thao 2026
                    </span>
                </div>

                <!-- Main dynamic heading -->
                <h1 class="text-5xl sm:text-6xl md:text-8xl font-heading font-extrabold tracking-tight text-white uppercase leading-none max-w-5xl mx-auto" id="hero-main-title">
                    Bắt đầu trận đấu<br class="hidden sm:inline">
                    của bạn trong <span class="text-[#39FF14] relative inline-block">30 Giây</span>
                </h1>

                <!-- Sub-heading description -->
                <p class="mt-6 text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-sans" id="hero-sub-description">
                    Tìm kiếm sân đấu gần nhất, đặt lịch thời gian thực và thanh toán hoàn toàn tự động qua <span class="text-[#FF5722] font-semibold">VietQR / Seepay</span>. Xác thực giao dịch trong 3 giây.
                </p>

                <!-- Actions -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#demo" 
                       class="w-full sm:w-auto px-8 py-4 bg-[#39FF14] text-black font-heading font-extrabold uppercase tracking-wider text-lg rounded-[2px] transition-all hover:bg-[#39FF14]/90 hover:scale-[1.02] hover:shadow-[0_0_20px_rgba(57,255,20,0.5)] text-center"
                       id="btn-hero-find-now">
                        Tìm Sân & Đặt Ngay
                    </a>
                    <button @click="showPartnerModal = true" 
                       class="w-full sm:w-auto px-8 py-4 bg-transparent border-2 border-[#FF5722] text-[#FF5722] font-heading font-extrabold uppercase tracking-wider text-lg rounded-[2px] transition-all hover:bg-[#FF5722] hover:text-white hover:scale-[1.02] text-center cursor-pointer"
                       id="btn-hero-owner-signup">
                        Trở Thành Đối Tác Sân
                    </button>
                </div>

                <!-- Instant stats count -->
                <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8 border-t border-gray-800 font-heading">
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-extrabold text-white">500+</div>
                        <div class="text-xs md:text-sm text-gray-500 uppercase tracking-widest mt-1">Sân Thể Thao</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-extrabold text-[#39FF14]">35K+</div>
                        <div class="text-xs md:text-sm text-gray-500 uppercase tracking-widest mt-1">Trận Đấu Đã Đặt</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-extrabold text-[#FF5722]">3 Giây</div>
                        <div class="text-xs md:text-sm text-gray-500 uppercase tracking-widest mt-1">Xác Thực Giao Dịch</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-extrabold text-white">99.99%</div>
                        <div class="text-xs md:text-sm text-gray-500 uppercase tracking-widest mt-1">Uptime Tự Động</div>
                    </div>
                </div>

            </section>

            <!-- 3. LIVE INTERACTIVE DEMO (WIDGET SIMULATION) -->
            <section class="bg-[#161F30]/40 border-y border-gray-800 py-20 relative" id="demo" x-data="{
                sport: 'football',
                selectedSlot: null,
                bookingStep: 0,
                timer: null,
                progress: 0,
                slots: {
                    football: [
                        { id: 1, time: '17:00 - 18:30', price: '600.000đ', status: 'available' },
                        { id: 2, time: '18:30 - 20:00', price: '750.000đ', status: 'booked' },
                        { id: 3, time: '20:00 - 21:30', price: '750.000đ', status: 'available' }
                    ],
                    badminton: [
                        { id: 4, time: '17:00 - 18:00', price: '90.000đ', status: 'available' },
                        { id: 5, time: '18:00 - 19:00', price: '120.000đ', status: 'available' },
                        { id: 6, time: '19:00 - 20:00', price: '120.000đ', status: 'booked' }
                    ],
                    tennis: [
                        { id: 7, time: '16:00 - 18:00', price: '250.000đ', status: 'booked' },
                        { id: 8, time: '18:00 - 20:00', price: '300.000đ', status: 'available' },
                        { id: 9, time: '20:00 - 22:00', price: '300.000đ', status: 'available' }
                    ]
                },
                selectSlot(slot) {
                    if (slot.status === 'booked') return;
                    this.selectedSlot = slot;
                    this.bookingStep = 1;
                    this.progress = 0;
                    
                    // Simulate generation
                    setTimeout(() => {
                        this.bookingStep = 2;
                        this.startPaymentSimulation();
                    }, 1200);
                },
                startPaymentSimulation() {
                    let interval = setInterval(() => {
                        if (this.progress < 100) {
                            this.progress += 5;
                        } else {
                            clearInterval(interval);
                            // Set slot to booked in UI
                            this.selectedSlot.status = 'booked';
                            this.bookingStep = 3;
                        }
                    }, 180);
                },
                resetSimulator() {
                    this.selectedSlot = null;
                    this.bookingStep = 0;
                    this.progress = 0;
                }
            }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-5xl font-heading font-extrabold uppercase tracking-tight text-white">
                            Trải Nghiệm Đặt Sân Tốc Độ Cao
                        </h2>
                        <p class="text-gray-400 font-sans max-w-xl mx-auto mt-2">
                            Thử tương tác trực tiếp với bảng đặt sân mô phỏng của chúng tôi bên dưới để cảm nhận tốc độ.
                        </p>
                    </div>

                    <!-- Simulator Console Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-5xl mx-auto">
                        
                        <!-- Left Panel: Sports selection & time slot grid (col-span-7) -->
                        <div class="lg:col-span-7 bg-[#161F30] border border-gray-800 p-6 rounded-[2px] flex flex-col justify-between">
                            <div>
                                <!-- Header section label -->
                                <div class="flex items-center justify-between pb-4 border-b border-gray-800 mb-6">
                                    <h3 class="font-heading font-bold text-xl uppercase tracking-wider text-white">
                                        Bảng Đặt Lịch Trực Tuyến
                                    </h3>
                                    <span class="text-xs text-[#39FF14] font-semibold bg-[#39FF14]/10 px-2 py-0.5 rounded-none uppercase">
                                        Live
                                    </span>
                                </div>

                                <!-- Sport type tabs -->
                                <div class="flex gap-2 mb-6">
                                    <button 
                                        @click="sport = 'football'; resetSimulator();" 
                                        :class="sport === 'football' ? 'bg-[#39FF14] text-black border-[#39FF14]' : 'bg-[#0B0F17] text-gray-400 border-gray-800 hover:text-white'"
                                        class="flex-1 py-3 px-2 border font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all flex items-center justify-center gap-2"
                                        id="btn-sport-football">
                                        ⚽ Bóng Đá
                                    </button>
                                    <button 
                                        @click="sport = 'badminton'; resetSimulator();" 
                                        :class="sport === 'badminton' ? 'bg-[#39FF14] text-black border-[#39FF14]' : 'bg-[#0B0F17] text-gray-400 border-gray-800 hover:text-white'"
                                        class="flex-1 py-3 px-2 border font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all flex items-center justify-center gap-2"
                                        id="btn-sport-badminton">
                                        🏸 Cầu Lông
                                    </button>
                                    <button 
                                        @click="sport = 'tennis'; resetSimulator();" 
                                        :class="sport === 'tennis' ? 'bg-[#39FF14] text-black border-[#39FF14]' : 'bg-[#0B0F17] text-gray-400 border-gray-800 hover:text-white'"
                                        class="flex-1 py-3 px-2 border font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all flex items-center justify-center gap-2"
                                        id="btn-sport-tennis">
                                        🎾 Tennis
                                    </button>
                                </div>

                                <!-- Dynamic availability list -->
                                <div class="space-y-3">
                                    <template x-for="slot in slots[sport]" :key="slot.id">
                                        <div 
                                            @click="selectSlot(slot)"
                                            :class="slot.status === 'booked' ? 'border-gray-800 bg-[#0B0F17]/40 opacity-60 cursor-not-allowed' : (selectedSlot && selectedSlot.id === slot.id ? 'border-[#39FF14] bg-[#39FF14]/5 cursor-pointer' : 'border-gray-800 bg-[#0B0F17] hover:border-gray-600 cursor-pointer')"
                                            class="p-4 border rounded-[2px] flex items-center justify-between transition-all"
                                            :id="'slot-item-' + slot.id">
                                            
                                            <!-- Time & Price details -->
                                            <div>
                                                <div class="font-heading font-extrabold text-lg tracking-wide text-white" x-text="slot.time"></div>
                                                <div class="text-sm text-gray-500 font-sans mt-0.5" x-text="'Giá: ' + slot.price"></div>
                                            </div>

                                            <!-- Status Tag & Action -->
                                            <div class="flex items-center gap-3">
                                                <span 
                                                    :class="slot.status === 'booked' ? 'text-gray-500 bg-gray-900 border-gray-800' : 'text-[#39FF14] bg-[#39FF14]/10 border-[#39FF14]/20'"
                                                    class="text-xs font-bold font-heading px-2 py-0.5 border uppercase tracking-wider rounded-none"
                                                    x-text="slot.status === 'booked' ? 'Đã Có Lịch' : 'Đang Trống'">
                                                </span>
                                                <template x-if="slot.status !== 'booked'">
                                                    <span class="w-6 h-6 bg-[#39FF14]/10 rounded-none border border-[#39FF14]/20 flex items-center justify-center group-hover:bg-[#39FF14]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#39FF14]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </span>
                                                </template>
                                            </div>

                                        </div>
                                    </template>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 font-sans mt-6">
                                * Ghi chú: Đây là dữ liệu mô phỏng thời gian thực của cụm sân.
                            </p>
                        </div>

                        <!-- Right Panel: The simulator output screens (col-span-5) -->
                        <div class="lg:col-span-5 bg-[#0B0F17] border border-gray-800 p-6 rounded-[2px] flex flex-col justify-between relative min-h-[380px] overflow-hidden">
                            
                            <!-- Grid decorative bg -->
                            <div class="absolute inset-0 grid-pattern pointer-events-none opacity-20"></div>

                            <!-- Screen Container -->
                            <div class="relative z-10 h-full flex flex-col justify-between">
                                
                                <!-- STEP 0: Idle state -->
                                <template x-if="bookingStep === 0">
                                    <div class="flex flex-col items-center justify-center text-center py-12 h-full my-auto">
                                        <div class="w-16 h-16 bg-[#161F30] border border-gray-800 flex items-center justify-center mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="square" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-heading font-extrabold text-2xl uppercase tracking-wider text-white">
                                            Đang Chờ Chọn Lịch
                                        </h4>
                                        <p class="text-sm text-gray-500 font-sans max-w-xs mt-2">
                                            Vui lòng chọn một khung giờ còn trống ở cột bên trái để bắt đầu thanh toán thử nghiệm.
                                        </p>
                                    </div>
                                </template>

                                <!-- STEP 1: Generating VietQR -->
                                <template x-if="bookingStep === 1">
                                    <div class="flex flex-col items-center justify-center text-center py-12 h-full my-auto">
                                        <div class="relative w-16 h-16 flex items-center justify-center mb-6">
                                            <!-- Simple spinner -->
                                            <div class="absolute inset-0 rounded-none border-2 border-t-[#39FF14] border-gray-800 animate-spin"></div>
                                        </div>
                                        <h4 class="font-heading font-extrabold text-2xl uppercase tracking-wider text-white">
                                            Khởi Tạo Đơn Hàng...
                                        </h4>
                                        <p class="text-sm text-[#39FF14] font-heading tracking-widest uppercase mt-2">
                                            Đang đồng bộ hóa API Seepay
                                        </p>
                                    </div>
                                </template>

                                <!-- STEP 2: Display QR & Pending payment -->
                                <template x-if="bookingStep === 2">
                                    <div class="flex flex-col items-center justify-between h-full">
                                        <div class="text-center w-full">
                                            <div class="flex justify-between items-center pb-3 border-b border-gray-800 mb-4">
                                                <span class="font-heading font-bold text-xs uppercase text-gray-500">
                                                    Đơn đặt: <span class="text-white">#PM-9942</span>
                                                </span>
                                                <span class="text-xs text-[#FF5722] font-semibold animate-pulse flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-none bg-[#FF5722]"></span>
                                                    Chờ chuyển khoản
                                                </span>
                                            </div>
                                            
                                            <div class="bg-white p-3 inline-block rounded-none shadow-[0_0_15px_rgba(255,255,255,0.05)] mb-4">
                                                <!-- Mock QR Code SVG -->
                                                <svg class="w-32 h-32 text-black" viewBox="0 0 100 100">
                                                    <rect width="100" height="100" fill="white"/>
                                                    <!-- QR Corners -->
                                                    <rect x="5" y="5" width="25" height="25" fill="black"/>
                                                    <rect x="9" y="9" width="17" height="17" fill="white"/>
                                                    <rect x="12" y="12" width="11" height="11" fill="black"/>

                                                    <rect x="70" y="5" width="25" height="25" fill="black"/>
                                                    <rect x="74" y="9" width="17" height="17" fill="white"/>
                                                    <rect x="77" y="12" width="11" height="11" fill="black"/>

                                                    <rect x="5" y="70" width="25" height="25" fill="black"/>
                                                    <rect x="9" y="74" width="17" height="17" fill="white"/>
                                                    <rect x="12" y="77" width="11" height="11" fill="black"/>

                                                    <!-- Center Logo Square (P) -->
                                                    <rect x="42" y="42" width="16" height="16" fill="black"/>
                                                    <rect x="44" y="44" width="12" height="12" fill="white"/>
                                                    <text x="47" y="53" font-family="sans-serif" font-weight="900" font-size="9" fill="black">P</text>

                                                    <!-- Random QR bits -->
                                                    <rect x="40" y="10" width="5" height="10" fill="black"/>
                                                    <rect x="50" y="5" width="10" height="5" fill="black"/>
                                                    <rect x="45" y="25" width="15" height="5" fill="black"/>
                                                    <rect x="5" y="40" width="10" height="5" fill="black"/>
                                                    <rect x="20" y="45" width="5" height="15" fill="black"/>
                                                    <rect x="10" y="55" width="15" height="5" fill="black"/>
                                                    <rect x="85" y="40" width="10" height="15" fill="black"/>
                                                    <rect x="70" y="50" width="5" height="10" fill="black"/>
                                                    <rect x="80" y="60" width="15" height="5" fill="black"/>
                                                    <rect x="40" y="70" width="15" height="5" fill="black"/>
                                                    <rect x="55" y="80" width="5" height="10" fill="black"/>
                                                    <rect x="35" y="85" width="15" height="5" fill="black"/>
                                                </svg>
                                            </div>

                                            <div class="text-sm font-sans text-gray-400">
                                                Quét mã VietQR bằng ứng dụng ngân hàng của bạn
                                            </div>
                                            <div class="text-lg font-heading font-extrabold text-white mt-1" x-text="'Thanh toán: ' + selectedSlot.price"></div>
                                        </div>

                                        <!-- Progress status of Seepay webhook simulation -->
                                        <div class="w-full mt-4">
                                            <div class="flex justify-between text-xs font-heading text-gray-500 mb-1">
                                                <span>Trình kiểm tra Seepay</span>
                                                <span x-text="progress + '%'"></span>
                                            </div>
                                            <div class="w-full h-1.5 bg-[#161F30] rounded-none overflow-hidden">
                                                <div class="h-full bg-[#39FF14] transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- STEP 3: Booking Success -->
                                <template x-if="bookingStep === 3">
                                    <div class="flex flex-col items-center justify-center text-center py-6 h-full my-auto">
                                        <div class="w-16 h-16 bg-[#39FF14]/10 border border-[#39FF14] flex items-center justify-center mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#39FF14]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <h4 class="font-heading font-extrabold text-2xl uppercase tracking-wider text-white">
                                            Thanh Toán Thành Công!
                                        </h4>
                                        <p class="text-sm text-gray-400 font-sans max-w-xs mt-2">
                                            Giao dịch chuyển khoản của bạn đã được xác nhận thành công qua webhook Seepay. Sân đã được đặt.
                                        </p>
                                        
                                        <!-- Actions inside widget -->
                                        <div class="mt-8 flex gap-3 w-full">
                                            <button 
                                                @click="resetSimulator()" 
                                                class="flex-1 py-3 px-4 bg-transparent border border-gray-800 text-gray-400 hover:text-white font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all"
                                                id="btn-simulator-reset">
                                                Thử Lại
                                            </button>
                                            <a 
                                                href="{{ route('register') }}"
                                                class="flex-1 py-3 px-4 bg-[#39FF14] text-black font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all hover:bg-[#39FF14]/90 text-center"
                                                id="btn-simulator-register">
                                                Đăng Ký Đặt Thật
                                            </a>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- 4. ASYMMETRIC FEATURES GRID -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24" id="features">
                
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-heading font-extrabold uppercase tracking-tight text-white">
                        Tính Năng Vận Hành Hàng Đầu
                    </h2>
                    <p class="text-gray-400 font-sans max-w-xl mx-auto mt-2">
                        Giải pháp số hóa toàn diện từ quy trình đặt sân của người chơi đến quản lý tài chính của chủ sân.
                    </p>
                </div>

                <!-- 3-Column Asymmetric Grid layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Card 1 (Double size, span-2): Interactive Map court finder -->
                    <div class="md:col-span-2 bg-[#161F30] border border-gray-800 p-8 rounded-[2px] flex flex-col justify-between hover:border-gray-700 transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xs text-[#39FF14] font-bold font-heading uppercase tracking-widest px-2 py-0.5 bg-[#39FF14]/10 rounded-none">
                                    Độc quyền
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 group-hover:text-[#39FF14] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="square" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="square" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="font-heading font-extrabold text-3xl text-white uppercase tracking-wider mb-3">
                                Tìm Sân Bản Đồ & Đặt Lịch Tức Thì
                            </h3>
                            <p class="text-gray-400 font-sans max-w-2xl">
                                Hệ thống tự động quét vị trí hiện tại của bạn, hiển thị danh sách các cụm sân bóng đá, cầu lông, tennis có lịch trống trong bán kính gần nhất. Trực quan hóa bản đồ, không còn mất thời gian gọi điện check lịch.
                            </p>
                        </div>
                        
                        <!-- Map Vector Simulation Graphic -->
                        <div class="mt-8 bg-[#0B0F17] border border-gray-800 h-48 rounded-[2px] relative overflow-hidden flex items-center justify-center">
                            <div class="absolute inset-0 grid-pattern opacity-10"></div>
                            
                            <!-- Vector Roads simulated with simple lines -->
                            <svg class="absolute inset-0 w-full h-full text-gray-800/20" viewBox="0 0 400 200">
                                <line x1="0" y1="50" x2="400" y2="50" stroke="currentColor" stroke-width="4"/>
                                <line x1="0" y1="150" x2="400" y2="150" stroke="currentColor" stroke-width="4"/>
                                <line x1="120" y1="0" x2="120" y2="200" stroke="currentColor" stroke-width="4"/>
                                <line x1="300" y1="0" x2="300" y2="200" stroke="currentColor" stroke-width="4"/>
                            </svg>

                            <!-- Pinging neon green court location markers -->
                            <div class="absolute top-[60px] left-[140px] flex flex-col items-center">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-none bg-[#39FF14] opacity-75"></span>
                                    <span class="relative inline-flex rounded-none h-3 w-3 bg-[#39FF14]"></span>
                                </span>
                                <div class="bg-[#161F30] border border-gray-800 text-[10px] font-heading px-2 py-0.5 text-white uppercase mt-1">
                                    Sân Thống Nhất (Cầu Lông)
                                </div>
                            </div>

                            <div class="absolute bottom-[70px] right-[120px] flex flex-col items-center">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-none bg-[#FF5722] opacity-75"></span>
                                    <span class="relative inline-flex rounded-none h-3 w-3 bg-[#FF5722]"></span>
                                </span>
                                <div class="bg-[#161F30] border border-gray-800 text-[10px] font-heading px-2 py-0.5 text-white uppercase mt-1">
                                    Sân Kì Hòa (Bóng Đá)
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Card 2 (Single size): Automated Webhook Checkout -->
                    <div class="md:col-span-1 bg-[#161F30] border border-gray-800 p-8 rounded-[2px] flex flex-col justify-between hover:border-gray-700 transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xs text-[#FF5722] font-bold font-heading uppercase tracking-widest px-2 py-0.5 bg-[#FF5722]/10 rounded-none">
                                    Seepay
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 group-hover:text-[#FF5722] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="square" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-heading font-extrabold text-3xl text-white uppercase tracking-wider mb-3">
                                Webhook Tự Động 3 Giây
                            </h3>
                            <p class="text-gray-400 font-sans">
                                Nhờ tích hợp cổng thanh toán Seepay trực tiếp, khi người chơi quét VietQR chuyển khoản ngân hàng, hệ thống ngay lập tức nhận diện giao dịch trong 3 giây và tự động duyệt khóa sân.
                            </p>
                        </div>
                        
                        <div class="mt-8 pt-4 border-t border-gray-800 flex items-center justify-between text-xs font-heading uppercase tracking-wide text-gray-500">
                            <span>API Status: Active</span>
                            <span class="text-[#39FF14]">Connected</span>
                        </div>
                    </div>

                    <!-- Card 3 (Single size): Analytics charts -->
                    <div class="md:col-span-1 bg-[#161F30] border border-gray-800 p-8 rounded-[2px] flex flex-col justify-between hover:border-gray-700 transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xs text-gray-400 font-bold font-heading uppercase tracking-widest px-2 py-0.5 bg-gray-800 rounded-none">
                                    Báo Cáo
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 group-hover:text-[#39FF14] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="square" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                            </div>
                            <h3 class="font-heading font-extrabold text-3xl text-white uppercase tracking-wider mb-3">
                                Thống Kê & Doanh Thu
                            </h3>
                            <p class="text-gray-400 font-sans">
                                Báo cáo chi tiết về tỷ lệ lấp đầy sân, số giờ vàng khai thác và doanh thu tài chính theo tuần/tháng. Biểu đồ trực quan giúp chủ sân dễ dàng tối ưu lịch khai thác sân bóng.
                            </p>
                        </div>
                        
                        <!-- High Contrast CSS Chart Mockup -->
                        <div class="mt-8 flex items-end gap-2 h-24 bg-[#0B0F17] p-4 border border-gray-800 rounded-none">
                            <div class="flex-1 bg-gray-800 h-[30%]"></div>
                            <div class="flex-1 bg-gray-800 h-[50%]"></div>
                            <div class="flex-1 bg-[#FF5722] h-[75%]"></div>
                            <div class="flex-1 bg-gray-800 h-[45%]"></div>
                            <div class="flex-1 bg-[#39FF14] h-[95%]"></div>
                        </div>
                    </div>

                    <!-- Card 4 (Double size, span-2): Admin management dashboard -->
                    <div class="md:col-span-2 bg-[#161F30] border border-gray-800 p-8 rounded-[2px] flex flex-col justify-between hover:border-gray-700 transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xs text-gray-400 font-bold font-heading uppercase tracking-widest px-2 py-0.5 bg-gray-800 rounded-none">
                                    Vận hành
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 group-hover:text-[#39FF14] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="square" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="square" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="font-heading font-extrabold text-3xl text-white uppercase tracking-wider mb-3">
                                Quản Lý Lịch Đấu &amp; Hoạt Động Cụm Sân
                            </h3>
                            <p class="text-gray-400 font-sans">
                                Bảng điều khiển mạnh mẽ dành riêng cho admin và chủ sân. Quản lý danh sách đặt sân, phân chia quyền hạn nhân viên trực ca, thiết lập khung giờ cao điểm và điều chỉnh bảng giá linh hoạt theo thời tiết hoặc ngày lễ.
                            </p>
                        </div>
                        
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <div class="flex-1 bg-[#0B0F17] border border-gray-800 p-4 rounded-none">
                                <div class="text-[10px] uppercase tracking-wider text-gray-500 font-heading">Lịch hôm nay</div>
                                <div class="text-xl font-heading font-extrabold text-white mt-1">48 Khung Giờ</div>
                            </div>
                            <div class="flex-1 bg-[#0B0F17] border border-gray-800 p-4 rounded-none">
                                <div class="text-[10px] uppercase tracking-wider text-gray-500 font-heading">Đã thanh toán</div>
                                <div class="text-xl font-heading font-extrabold text-[#39FF14] mt-1">42 Lịch Đặt (87%)</div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>

            <!-- 5. TESTIMONIALS SECTION -->
            <section class="bg-[#161F30]/20 py-24 border-t border-gray-800" id="testimonials">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-5xl font-heading font-extrabold uppercase tracking-tight text-white">
                            Khách Hàng Nói Về Chúng Tôi
                        </h2>
                        <p class="text-gray-400 font-sans max-w-xl mx-auto mt-2">
                            Xem những đánh giá chân thực từ chính những người chơi thể thao và các đối tác vận hành sân.
                        </p>
                    </div>

                    <!-- Testimonials grid with asymmetric items -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                        
                        <!-- Testimonial 1 -->
                        <div class="bg-[#161F30] border-l-2 border-[#39FF14] p-8 rounded-none relative">
                            <span class="absolute top-4 right-6 text-6xl font-heading font-extrabold text-gray-800/30 select-none">“</span>
                            <p class="text-gray-300 font-sans text-base leading-relaxed relative z-10">
                                &quot;Tôi và hội bạn đá bóng phong trào hàng tuần luôn gặp khó khăn khi liên hệ chủ sân để đặt lịch vào giờ cao điểm 18:30. Từ khi sử dụng PlayManagement, tôi chỉ mất 30 giây để tìm sân trống gần nhất, chọn giờ và thanh toán qua QR. Lịch đấu được chốt tự động cực kỳ tin cậy.&quot;
                            </p>
                            <div class="mt-6 flex items-center gap-3">
                                <span class="w-10 h-10 bg-gray-800 border border-gray-700 flex items-center justify-center font-heading font-bold text-white text-lg rounded-none">
                                    TA
                                </span>
                                <div>
                                    <div class="font-heading font-bold text-white uppercase tracking-wide">Trần Anh</div>
                                    <div class="text-xs text-gray-500 font-sans">Đội trưởng FC Phong Trào</div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="bg-[#161F30] border-l-2 border-[#FF5722] p-8 rounded-none relative">
                            <span class="absolute top-4 right-6 text-6xl font-heading font-extrabold text-gray-800/30 select-none">“</span>
                            <p class="text-gray-300 font-sans text-base leading-relaxed relative z-10">
                                &quot;Là một chủ cụm 5 sân bóng đá mini, trước đây việc quản lý đặt lịch qua Zalo và đối soát chuyển khoản ngân hàng thủ công làm tôi đau đầu, hay xảy ra trùng sân. Hệ thống quản lý vận hành của PlayManagement đã tự động hóa 95% công việc đó. Doanh thu tăng 25% nhờ giảm tối đa tỷ lệ hủy lịch.&quot;
                            </p>
                            <div class="mt-6 flex items-center gap-3">
                                <span class="w-10 h-10 bg-gray-800 border border-gray-700 flex items-center justify-center font-heading font-bold text-[#FF5722] text-lg rounded-none">
                                    MH
                                </span>
                                <div>
                                    <div class="font-heading font-bold text-white uppercase tracking-wide">Minh Hoàng</div>
                                    <div class="text-xs text-gray-500 font-sans">Chủ sân bóng Kỳ Hòa, Quận 10</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- 6. HIGH-CONTRAST CTA BLOCK -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center relative" id="cta">
                
                <div class="absolute inset-0 grid-pattern pointer-events-none opacity-20"></div>

                <div class="relative z-10 max-w-4xl mx-auto bg-[#161F30] border-2 border-[#39FF14]/30 p-8 md:p-16 rounded-[2px]">
                    <h2 class="text-4xl md:text-6xl font-heading font-extrabold uppercase tracking-tight text-white leading-none">
                        Sẵn sàng nâng cấp<br class="hidden sm:inline">
                        vận hành sân của bạn?
                    </h2>
                    
                    <p class="mt-4 text-gray-400 font-sans max-w-xl mx-auto text-base md:text-lg">
                        Đăng ký tài khoản ngay hôm nay để trải nghiệm quy trình đặt sân thể thao tự động hóa hoàn toàn mới.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto">
                        <a href="{{ route('register') }}" 
                           class="w-full sm:flex-1 py-4 bg-[#39FF14] text-black font-heading font-extrabold uppercase tracking-wider text-base rounded-[2px] transition-all hover:bg-[#39FF14]/90 hover:shadow-[0_0_15px_rgba(57,255,20,0.3)] text-center"
                           id="btn-cta-signup">
                            Đăng Ký Tài Khoản
                        </a>
                        <a href="{{ route('login') }}" 
                           class="w-full sm:flex-1 py-4 bg-transparent border border-gray-600 text-gray-300 hover:text-white font-heading font-extrabold uppercase tracking-wider text-base rounded-[2px] transition-all hover:border-gray-400 text-center"
                           id="btn-cta-login">
                            Đăng Nhập
                        </a>
                    </div>
                </div>

            </section>

        </main>

        <!-- 7. FOOTER SECTION -->
        <footer class="bg-[#0B0F17] border-t border-gray-800 py-12 relative z-10 text-xs text-gray-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                
                <!-- Brand details -->
                <div class="flex items-center gap-4">
                    <span class="font-heading font-extrabold text-sm tracking-widest text-[#39FF14]">
                        PLAYMANAGEMENT
                    </span>
                    <span class="text-gray-700">|</span>
                    <span>© {{ date('Y') }} PlayManagement Inc. All rights reserved.</span>
                </div>

                <!-- Footer link tags -->
                <div class="flex gap-6 font-heading uppercase tracking-wider">
                    <a href="#demo" class="hover:text-[#39FF14]">Đặt Sân</a>
                    <a href="#features" class="hover:text-[#39FF14]">Tính Năng</a>
                    <a href="#testimonials" class="hover:text-[#39FF14]">Ý Kiến</a>
                </div>

                <!-- Technology Badge -->
                <div class="flex items-center gap-2 font-mono text-[10px] text-gray-600">
                    <span>Laravel v{{ app()->version() }}</span>
                    <span>•</span>
                    <span>Vite v8.0</span>
                    <span>•</span>
                    <span>TailwindCSS v4.0</span>
                </div>

            </div>
        </footer>

        <!-- PARTNER REGISTRATION INFO MODAL -->
        <div x-show="showPartnerModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
             style="display: none;">
            
            <div @click.away="showPartnerModal = false" 
                 class="relative max-w-lg w-full bg-[#161F30] border-2 border-[#FF5722] p-8 text-left rounded-none shadow-[0_0_50px_rgba(255,87,34,0.25)]">
                
                <!-- Close Button -->
                <button @click="showPartnerModal = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-[#FF5722] transition-colors text-xl font-bold font-sans cursor-pointer">
                    ✕
                </button>

                <!-- Icon and Title -->
                <div class="flex items-center gap-4 mb-6 border-b border-gray-800 pb-4">
                    <span class="w-12 h-12 bg-[#FF5722]/10 text-[#FF5722] flex items-center justify-center rounded-[2px] border border-[#FF5722]/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 15v2m0-6h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h3 class="font-heading font-extrabold text-2xl uppercase tracking-wider text-white">
                        Hợp Tác Đối Tác Sân
                    </h3>
                </div>

                <!-- Content -->
                <p class="text-gray-300 font-sans text-sm leading-relaxed mb-6">
                    Để đảm bảo tính bảo mật và an toàn vận hành hệ thống, tài khoản dành cho **Chủ Sân (Owner)** không được đăng ký tự động mà sẽ do **Ban Quản Trị PlayManagement** kiểm tra, xác thực và cấp trực tiếp.
                </p>

                <!-- Contact details box -->
                <div class="bg-[#0B0F17] border border-gray-800 p-5 rounded-[2px] space-y-4 font-sans text-sm mb-6">
                    <div class="flex items-start gap-3">
                        <span class="font-heading font-bold text-gray-500 uppercase tracking-wider text-xs w-20 pt-0.5">Hotline:</span>
                        <a href="tel:0901234567" class="text-white hover:text-[#39FF14] font-bold transition-colors">0901 234 567</a>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="font-heading font-bold text-gray-500 uppercase tracking-wider text-xs w-20 pt-0.5">Email:</span>
                        <a href="mailto:partner@playmanagement.vn" class="text-white hover:text-[#39FF14] font-bold transition-colors">partner@playmanagement.vn</a>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="font-heading font-bold text-gray-500 uppercase tracking-wider text-xs w-20 pt-0.5">Hỗ Trợ:</span>
                        <span class="text-gray-400">24/7 đối với hệ thống tự động hóa VietQR</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="mailto:partner@playmanagement.vn?subject=Yeu%20cau%20hop%20tac%20chu%20san%20-%20PlayManagement" 
                       class="flex-1 py-3 px-4 bg-[#FF5722] hover:bg-[#FF5722]/90 text-white font-heading font-extrabold uppercase tracking-wider text-center text-sm rounded-[2px] transition-all cursor-pointer">
                        Gửi Email Hợp Tác
                    </a>
                    <button @click="showPartnerModal = false" 
                            class="flex-1 py-3 px-4 bg-transparent border border-gray-700 text-gray-400 hover:text-white font-heading font-bold uppercase tracking-wider text-sm rounded-[2px] transition-all cursor-pointer">
                        Đóng
                    </button>
                </div>

            </div>
        </div>

    </body>
</html>
