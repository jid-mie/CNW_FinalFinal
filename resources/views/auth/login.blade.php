<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold font-heading text-[#0f172a] uppercase tracking-wide">Đăng nhập</h2>
        <p class="text-[#45464d] mt-2 text-sm">Chào mừng trở lại! Vui lòng nhập thông tin để tiếp tục.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#76777d]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="block w-full pl-10 px-4 py-3 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="nhapemail@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Mật khẩu</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#76777d]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="block w-full pl-10 px-4 py-3 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-[#0f172a] border-[#c6c6cd] rounded focus:ring-[#0f172a] focus:ring-offset-0">
                <span class="ml-2 text-xs font-medium text-[#45464d]">Ghi nhớ đăng nhập</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#0f172a] hover:underline">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-wider text-[#4ade80] bg-[#0f172a] hover:bg-[#1e293b] hover:scale-[1.01] active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a] transition-all duration-150">
            Đăng nhập
        </button>

        <p class="text-center text-xs text-[#45464d] mt-6">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="font-bold text-[#0f172a] hover:underline">Đăng ký ngay</a>
        </p>
    </form>
</x-guest-layout>
