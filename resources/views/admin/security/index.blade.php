@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">
    <!-- Breadcrumbs -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> <span class="text-slate-600 font-semibold underline cursor-pointer">Cài đặt bảo mật</span>
        </div>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight uppercase">Giám sát & Cấu hình Bảo mật</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">Bảng điều khiển trung tâm giám sát đăng nhập sai, quản lý API hoạt động và kiểm duyệt lỗ hổng bảo mật.</p>
        </div>
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-4 py-2.5 rounded-xl font-bold flex items-center gap-1.5 shadow-sm animate-pulse">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. Vulnerability Audit Checklist (Left Column - 1/3) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Kiểm duyệt Lỗ hổng Hệ thống
                </h3>
                
                <div class="space-y-4">
                    @foreach($systemChecks as $check)
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-start gap-3">
                            <span class="mt-0.5">
                                @if($check['status'] === 'secure')
                                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                @elseif($check['status'] === 'warning')
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                @endif
                            </span>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide">{{ $check['name'] }}</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full 
                                        @if($check['status'] === 'secure') bg-emerald-100 text-emerald-800 
                                        @elseif($check['status'] === 'warning') bg-amber-100 text-amber-800 
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $check['value'] }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1 font-semibold leading-relaxed">{{ $check['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 text-[10px] text-slate-400 font-mono text-center">
                Mã hóa: OpenSSL 256-Bit • TLS 1.3
            </div>
        </div>

        <!-- 2. Active API Tokens & Login Failure Logs (Right Columns - 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active API Tokens Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        Quản lý API Tokens Đang Hoạt Động
                    </h3>
                </div>

                <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 font-semibold">Tên Token</th>
                                <th class="pb-3 font-semibold">Tài khoản Sở hữu</th>
                                <th class="pb-3 font-semibold">Quyền hạn (Abilities)</th>
                                <th class="pb-3 font-semibold">Dùng cuối</th>
                                <th class="pb-3 font-semibold">Trạng thái</th>
                                <th class="pb-3 text-right font-semibold">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            @forelse($tokens as $token)
                                <tr>
                                    <td class="py-3.5 font-mono text-slate-900">{{ $token->name }}</td>
                                    <td class="py-3.5">
                                        @if($token->tokenable)
                                            <div class="flex flex-col">
                                                <span class="text-slate-800 font-bold">{{ $token->tokenable->name }}</span>
                                                <span class="text-[10px] text-slate-400 font-medium">{{ $token->tokenable->email }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5">
                                        <span class="text-[10px] font-mono bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">
                                            {{ is_array($token->abilities) ? implode(', ', $token->abilities) : $token->abilities }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 font-mono text-[10px] text-slate-500">
                                        {{ $token->last_used_at ? $token->last_used_at->format('H:i:s d/m/Y') : 'Chưa sử dụng' }}
                                    </td>
                                    <td class="py-3.5">
                                        @if($token->is_active)
                                            <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span> Hoạt động
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full font-bold">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Vô hiệu hóa
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <form action="{{ route('admin.security.tokens.toggle', $token->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition 
                                                @if($token->is_active) 
                                                    text-amber-600 bg-amber-50 border-amber-100 hover:bg-amber-100 hover:text-amber-700
                                                @else 
                                                    text-emerald-600 bg-emerald-50 border-emerald-100 hover:bg-emerald-100 hover:text-emerald-700
                                                @endif">
                                                {{ $token->is_active ? 'Vô hiệu hóa' : 'Kích hoạt lại' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 font-medium">Không tìm thấy API Token hoạt động nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Login Failure Logs Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Giám Sát Nhật Ký Đăng Nhập Thất Bại (Thực Tế)
                    </h3>
                    
                    @if($failedAttempts->count() > 0)
                        <form action="{{ route('admin.security.logs.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ nhật ký đăng nhập sai?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold flex items-center gap-1.5 transition duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                <span>Xóa nhật ký</span>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="overflow-x-auto max-h-[350px] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 font-semibold">Tài khoản (Email)</th>
                                <th class="pb-3 font-semibold">IP Address</th>
                                <th class="pb-3 font-semibold">User Agent / Trình duyệt</th>
                                <th class="pb-3 text-right font-semibold">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            @forelse($failedAttempts as $attempt)
                                <tr class="hover:bg-slate-50 transition duration-150">
                                    <td class="py-3.5 text-slate-800 font-bold">{{ $attempt->email }}</td>
                                    <td class="py-3.5 font-mono text-[11px] text-slate-500">{{ $attempt->ip_address }}</td>
                                    <td class="py-3.5 text-[11px] text-slate-500 max-w-xs truncate" title="{{ $attempt->user_agent }}">
                                        {{ $attempt->user_agent }}
                                    </td>
                                    <td class="py-3.5 text-right font-mono text-[10px] text-slate-500">
                                        {{ $attempt->attempted_at->format('H:i:s d/m/Y') }}
                                        <span class="text-slate-400 text-[9px] block">({{ $attempt->attempted_at->diffForHumans() }})</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 font-medium">Hệ thống chưa ghi nhận bất kỳ nỗ lực đăng nhập sai nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
