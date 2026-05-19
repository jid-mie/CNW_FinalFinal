<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold font-heading text-[#0f172a] uppercase tracking-wide">Quên mật khẩu?</h2>
        <p class="text-[#45464d] mt-2 text-sm leading-relaxed">
            Đừng lo lắng! Vui lòng nhập địa chỉ email bạn đã sử dụng để đăng ký. Chúng tôi sẽ gửi cho bạn một liên kết để đặt lại mật khẩu.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Email của bạn</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#76777d]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                    class="block w-full pl-10 px-4 py-3 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="email@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-wider text-[#4ade80] bg-[#0f172a] hover:bg-[#1e293b] hover:scale-[1.01] active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a] transition-all duration-150">
            Gửi liên kết đặt lại mật khẩu
        </button>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs font-bold text-[#0f172a] hover:underline flex items-center justify-center gap-2 transition-colors">
                <svg class="w-4 h-4 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại đăng nhập
            </a>
        </div>
    </form>
</x-guest-layout>
