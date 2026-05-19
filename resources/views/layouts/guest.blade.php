<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PlayManagement') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Hanken Grotesk', sans-serif;
            }
            .font-heading {
                font-family: 'Archivo Narrow', sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased text-[#191c1e] bg-[#f8fafc] flex h-screen overflow-hidden">
        
        <!-- Left Side: Image/Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#0f172a] bg-cover bg-center relative items-center justify-center" 
             style="background-image: url('https://images.unsplash.com/photo-1518605368461-1e1e1146313b?q=80&w=2070&auto=format&fit=crop');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a]/95 to-[#131b2e]/85"></div>
            
            <!-- Branding Content -->
            <div class="relative z-10 text-center px-12 max-w-lg">
                <div class="flex justify-center mb-6">
                    <!-- High-contrast Sporty Logo SVG -->
                    <div class="w-16 h-16 bg-[#4ade80] rounded-xl flex items-center justify-center shadow-lg shadow-[#4ade80]/20">
                        <svg class="w-10 h-10 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4 tracking-tight font-heading uppercase">PlayManagement</h1>
                <p class="text-[#c6c6cd] text-base leading-relaxed font-normal">
                    Hệ thống quản lý sân thể thao thông minh, mang tính đột phá cho các cơ sở hiện đại. Đặt lịch nhanh chóng, quản lý tối ưu.
                </p>
                <div class="mt-8 flex justify-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#4ade80]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-white/20"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-white/20"></span>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 overflow-y-auto bg-[#f8fafc]">
            <div class="w-full max-w-md">
                <!-- Mobile Logo (Visible only on mobile) -->
                <div class="flex justify-center lg:hidden mb-8">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#0f172a] rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold font-heading uppercase tracking-wider text-[#0f172a]">PlayManagement</span>
                    </a>
                </div>

                <!-- Main Auth Form Content -->
                <div class="bg-white p-8 sm:p-10 rounded-xl border border-[#e2e8f0]" style="box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
