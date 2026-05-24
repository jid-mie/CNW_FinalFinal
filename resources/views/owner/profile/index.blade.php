<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Thông Tin Cá Nhân</h2>
    </x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
        @endif

        <!-- Avatar -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm mb-6">
            <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Ảnh đại diện</h3>
            <div class="flex items-center gap-4">
                <span class="w-16 h-16 rounded-full bg-[#0f172a] text-[#4ade80] text-xl font-bold flex items-center justify-center">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
                <form method="POST" action="{{ route('owner.profile.avatar') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" accept="image/*" class="text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f172a] file:text-[#4ade80] hover:file:bg-slate-800">
                    <button type="submit" class="ml-2 px-3 py-1.5 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50">Cập nhật</button>
                </form>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
            <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Thông tin</h3>
            <form method="POST" action="{{ route('owner.profile.update') }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Họ tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Địa chỉ</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                </div>
                <button type="submit" class="mt-4 px-6 py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800">Lưu thông tin</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm mt-6">
            <h3 class="font-bold text-sm text-[#0f172a] uppercase tracking-wide mb-4">Đổi mật khẩu</h3>
            <form method="POST" action="{{ route('owner.profile.update') }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Xác nhận mới</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                </div>
                <button type="submit" class="mt-4 px-6 py-2.5 border border-[#0f172a] text-[#0f172a] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-50">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</x-app-layout>
