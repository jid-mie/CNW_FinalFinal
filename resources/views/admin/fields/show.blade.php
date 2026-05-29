@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">
    
    <!-- Breadcrumb -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> 
            <a href="{{ route('admin.fields.index') }}" class="hover:text-slate-600 transition">Sân thể thao</a> 
            <span class="mx-1.5 text-slate-300">&gt;</span> 
            <span class="text-slate-600 font-semibold underline cursor-pointer">{{ $field->name }}</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight uppercase flex items-center gap-2">
                {{ $field->name }}
                <span class="font-mono text-xs text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    {{ $field->code ?? 'N/A' }}
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">Chi tiết thông tin sân, cấu hình khung giờ và lịch sử giao dịch đặt sân.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.fields.edit', $field->id) }}" class="bg-[#1e2538] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                <span>✏️</span> <span>Chỉnh sửa sân</span>
            </a>
            <a href="{{ route('admin.fields.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition border border-slate-200 shadow-sm flex items-center gap-1.5">
                <span>⬅️</span> <span>Quay lại</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Cột trái: Khung giờ & Lịch sử đặt sân -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Khung giờ hoạt động -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🕒</span> Khung giờ đặt sân hoạt động
                    </h3>
                    <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-100">
                        {{ count($field->timeSlots) }} KHUNG GIỜ
                    </span>
                </div>
                <div class="p-4">
                    @if(count($field->timeSlots) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($field->timeSlots as $slot)
                                <div class="p-3 rounded-xl border {{ $slot->is_active ? 'border-emerald-100 bg-[#e6fbf1]/20 text-[#10b981]' : 'border-slate-200 bg-slate-50 text-slate-400' }} flex flex-col items-center justify-center text-center">
                                    <span class="text-xs font-bold font-mono">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider mt-1 block">
                                        {{ $slot->is_active ? 'Kích hoạt' : 'Tạm dừng' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-400 text-xs font-medium">
                            Chưa cấu hình khung giờ cho sân này.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lịch sử đặt sân -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📅</span> Lịch sử đặt sân của khách hàng
                    </h3>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="p-4">Khách hàng</th>
                            <th class="p-4">Số điện thoại</th>
                            <th class="p-4">Ngày đặt</th>
                            <th class="p-4">Khung giờ</th>
                            <th class="p-4">Tổng tiền</th>
                            <th class="p-4">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-600 divide-y divide-slate-100 font-medium">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="p-4 font-bold text-slate-800">
                                    {{ $booking->customer->name ?? 'Khách vãng lai' }}
                                </td>
                                <td class="p-4 text-slate-500 font-mono">
                                    {{ $booking->customer->phone ?? 'N/A' }}
                                </td>
                                <td class="p-4 font-semibold text-slate-700">
                                    {{ $booking->booking_date->format('d/m/Y') }}
                                </td>
                                <td class="p-4 font-mono text-slate-600">
                                    {{ $booking->timeSlot ? $booking->timeSlot->start_time->format('H:i') . ' - ' . $booking->timeSlot->end_time->format('H:i') : 'N/A' }}
                                </td>
                                <td class="p-4 font-bold text-slate-800 font-mono">
                                    {{ number_format($booking->total_price) }}đ
                                </td>
                                <td class="p-4">
                                    @if($booking->status === 'pending')
                                        <span class="bg-yellow-50 text-yellow-600 text-[10px] font-black px-2 py-0.5 rounded-full border border-yellow-100 tracking-wider">CHỜ DUYỆT</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-full border border-blue-100 tracking-wider">ĐÃ XÁC NHẬN</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="bg-emerald-50 text-[#10b981] text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-100 tracking-wider">HOÀN THÀNH</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 text-[10px] font-black px-2 py-0.5 rounded-full border border-red-100 tracking-wider">ĐÃ HỦY</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-light">
                                    Sân thể thao này chưa có lịch sử đặt nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($bookings->hasPages())
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Cột phải: Thông tin thẻ chủ quản -->
        <div class="space-y-6">
            
            <!-- Thẻ Sân tổng thể (Sporty Navy Card) -->
            <div class="bg-[#1e2538] text-white rounded-2xl p-6 shadow-md relative overflow-hidden">
                <div class="relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block opacity-70">
                        Giá thuê sân hiện tại
                    </span>
                    <h2 class="text-4xl font-black mt-2 font-mono tracking-tight text-[#3cd882]">
                        {{ number_format($field->price_per_hour) }}đ <span class="text-xs text-slate-400 font-bold ml-1">/ Giờ</span>
                    </h2>

                    <div class="mt-6 space-y-4 border-t border-slate-700/50 pt-4 text-xs font-semibold">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Trạng thái vận hành:</span>
                            @if($field->status === 'active')
                                <span class="text-[#3cd882]">🟢 Đang hoạt động</span>
                            @else
                                <span class="text-red-400">🔴 Đang bảo trì</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Môn thể thao:</span>
                            <span class="text-white">{{ $field->sport->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Mã định danh sân:</span>
                            <span class="text-white font-mono">{{ $field->code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-slate-700/10 rounded-full blur-xl pointer-events-none"></div>
            </div>

            <!-- Thẻ Chủ sân & Địa chỉ -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-100 pb-2">
                    <span>👑</span> Thông tin Chủ quản lý & Địa chỉ
                </h4>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">Chủ sân:</span>
                        <span class="font-bold text-slate-800">{{ $field->owner->name ?? 'Chưa xác định' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Email liên hệ:</span>
                        <span class="font-mono text-slate-700">{{ $field->owner->email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Số điện thoại:</span>
                        <span class="font-mono text-slate-700">{{ $field->owner->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block mb-1">Địa điểm thực tế:</span>
                        <span class="font-semibold text-slate-700 leading-relaxed block">{{ $field->address }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
