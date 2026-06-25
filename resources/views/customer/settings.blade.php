<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Cài Đặt Tài Khoản</h2>
    </x-slot>
    
    <div class="py-6 max-w-2xl mx-auto">
        @if(session('success'))
            <div class="bg-green-50 border border-[#4ade80]/40 text-[#0f172a] px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Avatar -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm mb-6">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Ảnh Đại Diện
            </h3>
            <div class="flex flex-wrap items-center gap-5">
                @if($user->avatar)
                    <img src="{{ asset('uploads/avatars/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-[#e2e8f0] shadow-sm">
                @else
                    <span class="w-16 h-16 rounded-full bg-[#0f172a] text-[#4ade80] text-xl font-bold flex items-center justify-center shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                @endif
                <form method="POST" action="{{ route('customer.settings.avatar') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <input type="file" name="avatar" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f172a] file:text-[#4ade80] hover:file:bg-slate-800 transition-all cursor-pointer">
                    <button type="submit" class="px-4 py-2 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors bg-white shadow-sm">Cập nhật ảnh</button>
                </form>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Thông Tin Cá Nhân
            </h3>
            <form method="POST" action="{{ route('customer.settings.profile') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Họ tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Địa chỉ Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm bg-slate-50 text-slate-400 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Địa chỉ</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">Lưu thay đổi</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm mt-6">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Đổi Mật Khẩu
            </h3>
            <form method="POST" action="{{ route('customer.settings.password') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Mật khẩu mới</label>
                        <input type="password" name="new_password" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                </div>
                <button type="submit" class="mt-4 px-6 py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">Đổi mật khẩu</button>
            </form>
        </div>

        <!-- Language & Theme -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm mt-6">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Tùy Chỉnh Giao Diện
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Language -->
                <form method="POST" action="{{ route('customer.settings.language') }}">
                    @csrf
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Ngôn ngữ</label>
                    <div class="flex gap-3">
                        <button type="submit" name="language" value="vi" class="flex-1 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $user->language_preference === 'vi' ? 'bg-[#0f172a] text-[#4ade80] shadow-sm' : 'border border-[#e2e8f0] hover:bg-slate-50' }}">
                            🇻🇳 Tiếng Việt
                        </button>
                        <button type="submit" name="language" value="en" class="flex-1 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $user->language_preference === 'en' ? 'bg-[#0f172a] text-[#4ade80] shadow-sm' : 'border border-[#e2e8f0] hover:bg-slate-50' }}">
                            🇬🇧 English
                        </button>
                    </div>
                </form>
                <!-- Theme -->
                <form method="POST" action="{{ route('customer.settings.theme') }}">
                    @csrf
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Chủ đề</label>
                    <div class="flex gap-3">
                        <button type="submit" name="theme" value="light" class="flex-1 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $user->theme_preference === 'light' || !$user->theme_preference ? 'bg-[#0f172a] text-[#4ade80] shadow-sm' : 'border border-[#e2e8f0] hover:bg-slate-50' }}">
                            ☀️ Sáng
                        </button>
                        <button type="submit" name="theme" value="dark" class="flex-1 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $user->theme_preference === 'dark' ? 'bg-[#0f172a] text-[#4ade80] shadow-sm' : 'border border-[#e2e8f0] hover:bg-slate-50' }}">
                            🌙 Tối
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>