<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Khung Giờ — {{ $field->name }}</h2>
            <a href="{{ route('owner.fields.index') }}" class="text-xs font-bold text-[#0f172a] underline">← Sân</a>
        </div>
    </x-slot>
    <div class="py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if(session('success'))
            <div class="lg:col-span-3 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="lg:col-span-3 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <!-- Add New Slot -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm h-fit">
            <h3 class="font-bold font-heading text-sm text-[#0f172a] uppercase tracking-wide mb-4">Thêm Khung Giờ</h3>
            <form method="POST" action="{{ route('owner.time-slots.store', $field) }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] block mb-1">Giờ bắt đầu</label>
                        <input type="time" name="start_time" required class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] block mb-1">Giờ kết thúc</label>
                        <input type="time" name="end_time" required class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#45464d] block mb-1">Giá (VNĐ)</label>
                        <input type="number" name="price" value="{{ $field->price_per_hour }}" min="0" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                    </div>
                    <button type="submit" class="w-full py-2 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800">Thêm</button>
                </div>
            </form>
            <div class="mt-4 pt-4 border-t border-[#e2e8f0]">
                <form method="POST" action="{{ route('owner.time-slots.generate-default', $field) }}">
                    @csrf
                    <button type="submit" class="w-full py-2 border border-[#0f172a] text-[#0f172a] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-50">Tạo Mặc Định (06-22h)</button>
                </form>
            </div>
        </div>

        <!-- Slot List -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
            <h3 class="font-bold font-heading text-sm text-[#0f172a] uppercase tracking-wide mb-4">Danh Sách Khung Giờ</h3>
            @if($timeSlots->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#e2e8f0] text-xs uppercase tracking-wider text-[#45464d]">
                                <th class="text-left py-2 font-semibold">Giờ</th>
                                <th class="text-right py-2 font-semibold">Giá</th>
                                <th class="text-center py-2 font-semibold">Trạng thái</th>
                                <th class="text-right py-2 font-semibold">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeSlots as $slot)
                            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50">
                                <form method="POST" action="{{ route('owner.time-slots.update', $slot) }}">
                                    @csrf @method('PUT')
                                    <td class="py-3">
                                        <input type="time" name="start_time" value="{{ $slot->start_time->format('H:i') }}" class="w-20 px-2 py-1 border border-[#e2e8f0] rounded text-xs">
                                        <span class="mx-1">→</span>
                                        <input type="time" name="end_time" value="{{ $slot->end_time->format('H:i') }}" class="w-20 px-2 py-1 border border-[#e2e8f0] rounded text-xs">
                                    </td>
                                    <td class="py-3">
                                        <input type="number" name="price" value="{{ $slot->price ?? $field->price_per_hour }}" class="w-24 px-2 py-1 border border-[#e2e8f0] rounded text-xs text-right" min="0">
                                    </td>
                                    <td class="py-3 text-center">
                                        <select name="status" class="px-2 py-1 border border-[#e2e8f0] rounded text-xs">
                                            <option value="active" {{ $slot->status == 'active' ? 'selected' : '' }}>Mở</option>
                                            <option value="inactive" {{ $slot->status == 'inactive' ? 'selected' : '' }}>Tắt</option>
                                        </select>
                                    </td>
                                    <td class="py-3 text-right">
                                        <button type="submit" class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50">Lưu</button>
                                </form>
                                        <form method="POST" action="{{ route('owner.time-slots.destroy', $slot) }}" class="inline" onsubmit="return confirm('Xoá khung giờ này?')">
                                            @csrf @method('DELETE')
                                            <button class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-red-200 text-red-700 rounded-lg hover:bg-red-50">Xoá</button>
                                        </form>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $timeSlots->links() }}</div>
            @else
                <p class="text-sm text-[#45464d] py-8 text-center">Chưa có khung giờ nào. Tạo mặc định hoặc thêm thủ công.</p>
            @endif
        </div>
    </div>
</x-app-layout>
