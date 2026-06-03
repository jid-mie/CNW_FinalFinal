<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Họ và tên -->
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="name">Họ và Tên <span class="text-red-500">*</span></label>
        <div class="relative">
            <input class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg pl-4 pr-10 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all" 
                   id="name" name="name" type="text" placeholder="Nhập họ và tên" value="{{ old('name', $user->name ?? '') }}" required>
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
        </div>
        @error('name')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Địa chỉ Email -->
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="email">Địa chỉ Email <span class="text-red-500">*</span></label>
        <div class="relative">
            <input class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg pl-4 pr-10 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all" 
                   id="email" name="email" type="email" placeholder="email@viethan.vn" value="{{ old('email', $user->email ?? '') }}" required>
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </span>
        </div>
        @error('email')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Số điện thoại -->
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="phone">Số điện thoại</label>
        <div class="relative">
            <input class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg pl-4 pr-10 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all" 
                   id="phone" name="phone" type="text" placeholder="Nhập số điện thoại" value="{{ old('phone', $user->phone ?? '') }}">
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            </span>
        </div>
        @error('phone')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Vai trò tài khoản -->
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="role_id">Vai trò thành viên <span class="text-red-500">*</span></label>
        <div class="relative">
            <select class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg px-4 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all appearance-none" 
                    id="role_id" name="role_id" required>
                <option value="">-- Chọn khách hàng / chủ sân --</option>
                @foreach (($roles ?? []) as $role)
                    <option value="{{ $role->id }}" {{ (string) old('role_id', optional($user ?? null)->role_id ?? '') === (string) $role->id ? 'selected' : '' }}>
                        {{ $role->display_name === 'Customer' ? 'Khách hàng' : ($role->display_name === 'Owner' ? 'Chủ sân' : ($role->display_name ?? $role->name)) }}
                    </option>
                @endforeach
            </select>
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
        @error('role_id')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Địa chỉ liên hệ -->
    <div class="space-y-1.5 md:col-span-2">
        <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="address">Địa chỉ liên hệ</label>
        <div class="relative">
            <input class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg pl-4 pr-10 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all" 
                   id="address" name="address" type="text" placeholder="Nhập địa chỉ nhà, tên đường, khu vực" value="{{ old('address', $user->address ?? '') }}">
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </span>
        </div>
        @error('address')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Mật khẩu truy cập -->
    <div class="space-y-1.5 md:col-span-2">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-bold text-[#0f172a] uppercase tracking-wider" for="password">
                Mật khẩu {{ isset($user) ? '' : '*' }}
            </label>
            @if (isset($user))
                <span class="text-[10px] font-bold text-[#45464d] bg-slate-100 px-2 py-0.5 rounded uppercase">Để trống nếu giữ nguyên</span>
            @endif
        </div>
        <div class="relative">
            <input class="w-full bg-white border border-[#e2e8f0] text-sm text-[#0f172a] rounded-lg pl-4 pr-10 py-2.5 focus:border-[#0f172a] focus:ring-1 focus:ring-[#0f172a] outline-none transition-all" 
                   id="password" name="password" type="password" placeholder="{{ isset($user) ? '••••••••' : 'Nhập mật khẩu (tối thiểu 8 ký tự)' }}" {{ isset($user) ? '' : 'required' }}>
            <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
        </div>
        @error('password')
            <p class="text-red-500 text-xs font-bold mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
