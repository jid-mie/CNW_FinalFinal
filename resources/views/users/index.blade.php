<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Quản lý Tài khoản') }}
            </h2>
            <span class="bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                Khách hàng & Chủ sân
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-[#4ade80] rounded-r-xl flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <div class="p-1 bg-[#0f172a] text-[#4ade80] rounded-full mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-[#0f172a]">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] transition-all">
            <div class="p-6 border-b border-[#e2e8f0] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold font-heading text-[#0f172a] uppercase tracking-wide">Danh sách thành viên</h3>
                    <p class="text-xs text-[#45464d] mt-1">Danh sách tất cả tài khoản Khách hàng và Chủ sân trong hệ thống.</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider rounded-lg transition-colors hover:bg-slate-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Thêm tài khoản
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#e2e8f0]">
                            <th class="py-3.5 px-6 text-xs font-bold text-[#0f172a] uppercase tracking-wider w-16">ID</th>
                            <th class="py-3.5 px-6 text-xs font-bold text-[#0f172a] uppercase tracking-wider">Tên hiển thị</th>
                            <th class="py-3.5 px-6 text-xs font-bold text-[#0f172a] uppercase tracking-wider">Địa chỉ Email</th>
                            <th class="py-3.5 px-6 text-xs font-bold text-[#0f172a] uppercase tracking-wider w-32">Vai trò</th>
                            <th class="py-3.5 px-6 text-xs font-bold text-[#0f172a] uppercase tracking-wider w-48 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 text-sm font-semibold text-[#45464d]">{{ $user->id }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-[#e2e8f0] flex items-center justify-center font-bold text-xs text-[#0f172a] mr-3">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-bold text-[#0f172a]">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-[#45464d]">{{ $user->email }}</td>
                                <td class="py-4 px-6">
                                    @if($user->hasRole('owner'))
                                        <span class="inline-flex items-center bg-[#0f172a] text-[#4ade80] text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                                            Chủ sân
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full border border-slate-200">
                                            Khách hàng
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center p-2 text-slate-700 hover:text-slate-900 bg-white border border-[#e2e8f0] rounded-lg shadow-sm hover:bg-slate-50 transition-colors" title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center p-2 text-slate-700 hover:text-slate-900 bg-white border border-[#e2e8f0] rounded-lg shadow-sm hover:bg-slate-50 transition-colors" title="Chỉnh sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không? Hành động này không thể hoàn tác.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors" title="Xóa tài khoản">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-[#45464d] text-sm">
                                    <div class="flex flex-col items-center justify-center py-4">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="font-semibold text-slate-500">Chưa có tài khoản khách hàng hoặc chủ sân nào được đăng ký.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-6 border-t border-[#e2e8f0] bg-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
