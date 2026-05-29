<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VenuePro Management') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#191c1e] bg-[#f8fafc]">
    
    @if(auth()->check())
        <div class="flex min-h-screen bg-[#f8fafc]">
            
            <aside class="w-64 bg-[#1e2538] text-slate-300 flex flex-col justify-between fixed h-screen z-50 shadow-2xl border-r border-slate-700/30">
                <div>
                    <div class="p-6 border-b border-slate-700/40 flex items-center gap-3 bg-[#1e2538]">
                        <div class="w-9 h-9 bg-[#3cd882] rounded-xl flex items-center justify-center shadow-lg shadow-[#3cd882]/20">
                            <svg class="w-5.5 h-5.5 text-[#1e2538]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-bold text-base text-[#3cd882] tracking-wide leading-none">VenuePro</h1>
                            <p class="text-[9px] text-slate-400 font-medium tracking-wider mt-1 uppercase">Elite Management</p>
                        </div>
                    </div>

                    <nav class="p-4 space-y-1 mt-2">
                        
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#3cd882] text-[#1e2538]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>📊</span> <span>Dashboard</span>
                            </a>

                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-[#3cd882] text-[#1e2538]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>👥</span> <span>Users</span>
                            </a>

                            <a href="{{ route('admin.sports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('admin.sports.*') ? 'bg-[#3cd882] text-[#1e2538]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>⚽</span> <span>Sports</span>
                            </a>

                            <a href="{{ route('admin.fields.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('admin.fields.*') ? 'bg-[#3cd882] text-[#1e2538]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>🏢</span> <span>Fields</span>
                            </a>

                            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-[#3cd882] text-[#1e2538]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>💳</span> <span>Payments</span>
                            </a>

                        @elseif(auth()->user()->hasRole('owner'))
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>📊</span> <span>Dashboard</span>
                            </a>

                            <a href="{{ route('owner.fields.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.fields.*') || request()->routeIs('owner.time-slots.*') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>🏢</span> <span>Sân của tôi</span>
                            </a>

                            <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.bookings.*') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>📅</span> <span>Đặt lịch</span>
                            </a>

                            <a href="{{ route('owner.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.customers.*') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>👤</span> <span>Khách hàng</span>
                            </a>

                            <a href="{{ route('owner.revenue.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.revenue.*') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>💰</span> <span>Doanh thu</span>
                            </a>

                            <a href="{{ route('owner.profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('owner.profile.*') ? 'bg-[#3cd882]/10 text-[#3cd882]' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                                <span>⚙️</span> <span>Cài đặt</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <div class="p-4 border-t border-slate-700/40 bg-[#161b29]">
                    <div class="flex items-center gap-3 mb-4 px-2">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                        @else
                            <span class="w-9 h-9 rounded-full bg-slate-800 text-[#3cd882] flex items-center justify-center text-sm font-bold border border-slate-700">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate uppercase tracking-wider font-bold mt-0.5">
                                {{ auth()->user()->hasRole('admin') ? 'Hệ thống Admin' : 'Chủ sân thể thao' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200">
                            <span>🚪</span> <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 pl-64 min-h-screen flex flex-col">
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
                    <div class="relative w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
                        <input type="text" placeholder="Tìm kiếm trong hệ thống..." class="w-full bg-slate-50 text-xs pl-9 pr-4 py-2 rounded-xl border border-slate-200/60 focus:outline-none focus:border-slate-400 transition-colors">
                    </div>
                    
                    <div class="flex items-center space-x-4 text-slate-400 text-sm">
                        <button class="hover:text-slate-700 transition">🔔</button>
                        <button class="hover:text-slate-700 transition">⚙️</button>
                    </div>
                </header>

                <main class="flex-1 bg-[#f8fafc]">
                    @if (session('success'))
                        <div class="p-6 pb-0">
                            <div class="max-w-7xl mx-auto bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
                                <p class="text-xs font-bold text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="w-full">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        </div>
    @else
        <div class="min-h-screen bg-gray-100">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    @endif

</body>
</html>