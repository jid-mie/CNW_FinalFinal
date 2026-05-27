<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                Thêm Sân Mới
            </h2>
            <a href="{{ route('owner.fields.index') }}" class="text-xs font-bold text-[#0f172a] hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('owner.fields.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Tên sân <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Sân cỏ nhân tạo 7 người A" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Môn thể thao <span class="text-red-500">*</span></label>
                    <select name="sport_id" required class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="">Chọn môn thể thao</option>
                        @foreach($sports as $s)
                            <option value="{{ $s->id }}" {{ old('sport_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Mã sân</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="Ví dụ: FIELD_A" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Giá / giờ (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}" required min="0" placeholder="Ví dụ: 150000" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Trạng thái</label>
                    <select name="status" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Giờ mở cửa</label>
                    <input type="time" name="open_time" value="{{ old('open_time', '06:00') }}" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Giờ đóng cửa</label>
                    <input type="time" name="close_time" value="{{ old('close_time', '22:00') }}" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Ảnh sân</label>
                    <div class="border border-dashed border-slate-300 rounded-lg p-4 bg-slate-50 flex items-center justify-between">
                        <input type="file" name="image" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f172a] file:text-[#4ade80] hover:file:bg-slate-800 transition-all cursor-pointer">
                        <span class="text-[11px] text-[#45464d]">Hỗ trợ JPG, PNG, WEBP</span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Số nhà, ngõ, đường, quận/huyện..." class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Mô tả</label>
                    <textarea name="description" rows="4" placeholder="Nhập mô tả chi tiết về sân (chất lượng cỏ, dịch vụ đi kèm, lưu ý...)" class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-8 flex gap-3 pt-4 border-t border-[#e2e8f0]">
                <button type="submit" class="px-6 py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">Thêm sân</button>
                <a href="{{ route('owner.fields.index') }}" class="px-6 py-2.5 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors">Huỷ</a>
            </div>
        </form>
    </div>
</x-app-layout>
