<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Chi tiết Tài khoản') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-white text-slate-700 border border-[#e2e8f0] text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] transition-all">
            <div class="p-6 border-b border-[#e2e8f0] bg-slate-50 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-14 h-14 rounded-full bg-[#0f172a] text-[#4ade80] border border-[#0f172a] flex items-center justify-center font-bold text-xl mr-4 shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#0f172a]">{{ $user->name }}</h3>
                        <p class="text-xs text-[#45464d] mt-0.5">Tài khoản đăng ký từ {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div>
                    @if($user->hasRole('owner'))
                        <span class="inline-flex items-center bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm">
                            Chủ sân
                        </span>
                    @else
                        <span class="inline-flex items-center bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full border border-slate-200">
                            Khách hàng
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 divide-y divide-[#e2e8f0]">
                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Mã tài khoản (ID)</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">#{{ $user->id }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Họ và Tên</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->name }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Địa chỉ Email</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->email }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Số điện thoại</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->phone ?? 'Chưa cập nhật' }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Địa chỉ liên hệ</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->address ?? 'Chưa cập nhật' }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Ngày tạo tài khoản</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->created_at->format('H:i, d\/\m\/\Y') }}</dd>
                </div>

                <div class="py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-bold text-[#45464d] uppercase tracking-wider">Cập nhật lần cuối</dt>
                    <dd class="text-sm text-[#0f172a] font-semibold col-span-2">{{ $user->updated_at->format('H:i, d\/\m\/\Y') }}</dd>
                </div>
            </div>

            <div class="p-6 bg-slate-50 border-t border-[#e2e8f0] flex justify-end gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Chỉnh sửa thông tin
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
