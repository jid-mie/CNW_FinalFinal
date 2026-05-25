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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#191c1e] bg-[#f8fafc]">
        @if(auth()->check() && auth()->user()->hasRole('owner'))
            <div class="flex min-h-screen bg-[#f8fafc]">
                <!-- Sidebar -->
                <aside class="w-64 bg-[#0f172a] text-slate-300 flex flex-col justify-between fixed h-screen z-30 border-r border-slate-800">
                    <div>
                        <!-- Brand/Logo -->
                        <div class="p-6 border-b border-slate-800 flex items-center gap-3 bg-[#0f172a]">
                            <div class="w-9 h-9 bg-[#4ade80] rounded-xl flex items-center justify-center shadow-lg shadow-[#4ade80]/20">
                                <svg class="w-5.5 h-5.5 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold font-heading uppercase tracking-wider text-white">Arena Manager</span>
                        </div>

                        <!-- Navigation Links -->
                        <nav class="p-4 space-y-1.5 mt-4">
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('owner.fields.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.fields.*') || request()->routeIs('owner.time-slots.*') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Sân của tôi
                            </a>

                            <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.bookings.*') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Đặt lịch
                            </a>

                            <a href="{{ route('owner.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.customers.*') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Khách hàng
                            </a>

                            <a href="{{ route('owner.revenue.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.revenue.*') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Doanh thu
                            </a>

                            <a href="{{ route('owner.profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.profile.*') ? 'bg-[#4ade80]/10 text-[#4ade80]' : 'text-slate-450 hover:bg-slate-800/60 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Cài đặt
                            </a>
                        </nav>
                    </div>

                    <!-- Profile snippet & Logout -->
                    <div class="p-4 border-t border-slate-800">
                        <div class="flex items-center gap-3 mb-4 px-2">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                            @else
                                <span class="w-9 h-9 rounded-full bg-slate-800 text-[#4ade80] flex items-center justify-center text-sm font-bold border border-slate-700">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">Chủ sân</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Content Area -->
                <div class="flex-1 pl-64 min-h-screen flex flex-col">
                    <!-- Header for Page actions / Breadcrumbs -->
                    @isset($header)
                        <header class="bg-white border-b border-[#e2e8f0] py-5 px-8 flex items-center justify-between">
                            <div>
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1 p-8">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </main>
                </div>
            </div>
        @else
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        @endif
    </body>
</html>
