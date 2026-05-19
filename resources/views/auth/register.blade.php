<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold font-heading text-[#0f172a] uppercase tracking-wide">Tạo tài khoản mới</h2>
        <p class="text-[#45464d] mt-2 text-sm">Điền thông tin bên dưới để tham gia PlayManagement.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Họ và tên</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="Nguyễn Văn A">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="email@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Mật khẩu</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Xác nhận mật khẩu</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-[#ba1a1a]" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center py-3.5 px-4 mt-6 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-wider text-[#4ade80] bg-[#0f172a] hover:bg-[#1e293b] hover:scale-[1.01] active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a] transition-all duration-150">
            Đăng ký
        </button>

        <p class="text-center text-xs text-[#45464d] mt-6">
            Đã có tài khoản? 
            <a href="{{ route('login') }}" class="font-bold text-[#0f172a] hover:underline">Đăng nhập</a>
        </p>
    </form>
</x-guest-layout>
