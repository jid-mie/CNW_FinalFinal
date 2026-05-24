<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Quản lý khung giờ — {{ $field->name }}</h2>
            <a href="{{ route('owner.fields.index') }}" class="text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Quay lại sân đấu
            </a>
        </div>
    </x-slot>

    <div class="py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if(session('success'))
            <div class="lg:col-span-3 bg-green-50 border border-[#4ade80]/40 text-[#0f172a] px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="lg:col-span-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Add New Slot -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-sm h-fit">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Thêm Khung Giờ Mới
            </h3>
            
            <form method="POST" action="{{ route('owner.time-slots.store', $field) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Giờ bắt đầu</label>
                        <input type="time" name="start_time" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Giờ kết thúc</label>
                        <input type="time" name="end_time" required class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">
                        Thêm khung giờ
                    </button>
                </div>
            </form>
            
            <div class="mt-5 pt-4 border-t border-[#e2e8f0]">
                <form method="POST" action="{{ route('owner.time-slots.generate-default', $field) }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 border border-[#e2e8f0] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors bg-white shadow-sm flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        Khởi tạo khung giờ mặc định
                    </button>
                    <p class="text-[10px] text-slate-500 mt-2 text-center">Tự động tạo khung giờ liên tiếp (mỗi ca 1 tiếng) từ 06:00 đến 22:00.</p>
                </form>
            </div>
        </div>

        <!-- Slot List -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-[#e2e8f0] p-5 shadow-sm">
            <h3 class="font-bold font-heading text-xs text-[#0f172a] uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <span class="w-1.5 h-3 bg-[#0f172a] rounded"></span>
                Danh Sách Khung Giờ Hiện Có
            </h3>
            
            @if($timeSlots->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-[#e2e8f0] text-[10px] uppercase tracking-wider text-[#45464d]">
                                <th class="text-left px-4 py-3 font-bold">Khung giờ hoạt động</th>
                                <th class="text-center px-4 py-3 font-bold">Trạng thái</th>
                                <th class="text-right px-4 py-3 font-bold">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @foreach($timeSlots as $slot)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <form method="POST" action="{{ route('owner.time-slots.update', $slot) }}">
                                    @csrf @method('PUT')
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <input type="time" name="start_time" value="{{ $slot->start_time->format('H:i') }}" class="px-2.5 py-1 border border-[#e2e8f0] focus:ring-1 focus:ring-[#0f172a] focus:outline-none rounded text-xs">
                                            <span class="text-slate-400 font-bold">→</span>
                                            <input type="time" name="end_time" value="{{ $slot->end_time->format('H:i') }}" class="px-2.5 py-1 border border-[#e2e8f0] focus:ring-1 focus:ring-[#0f172a] focus:outline-none rounded text-xs">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <select name="status" class="px-2.5 py-1 border border-[#e2e8f0] focus:ring-1 focus:ring-[#0f172a] focus:outline-none rounded text-xs bg-white">
                                            <option value="active" {{ $slot->is_active ? 'selected' : '' }}>Đang mở</option>
                                            <option value="inactive" {{ !$slot->is_active ? 'selected' : '' }}>Tạm tắt</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-[#0f172a] text-[#4ade80] rounded hover:bg-slate-800 transition-colors shadow-sm">Lưu</button>
                                </form>
                                            <form method="POST" action="{{ route('owner.time-slots.destroy', $slot) }}" class="inline" onsubmit="return confirm('Xoá khung giờ này?')">
                                                @csrf @method('DELETE')
                                                <button class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider border border-red-200 text-red-700 rounded hover:bg-red-50 transition-colors">Xoá</button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $timeSlots->links() }}</div>
            @else
                <div class="text-center py-12 text-sm text-[#45464d]">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Chưa có khung giờ nào được thiết lập. Hãy khởi tạo khung giờ mặc định hoặc thêm thủ công.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
