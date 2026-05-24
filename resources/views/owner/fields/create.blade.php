<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Thêm Sân Mới</h2>
            <a href="{{ route('owner.fields.index') }}" class="text-xs font-bold text-[#0f172a] underline">← Quay lại</a>
        </div>
    </x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('owner.fields.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-[#e2e8f0] p-6 shadow-sm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Tên sân <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Môn thể thao <span class="text-red-500">*</span></label>
                    <select name="sport_id" required class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        @foreach($sports as $s)
                            <option value="{{ $s->id }}" {{ old('sport_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Mã sân</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Giá / giờ (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}" required min="0" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Giờ mở cửa</label>
                    <input type="time" name="open_time" value="{{ old('open_time', '06:00') }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Giờ đóng cửa</label>
                    <input type="time" name="close_time" value="{{ old('close_time', '22:00') }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Trạng thái</label>
                    <select name="status" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Ảnh sân</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f172a] file:text-[#4ade80] hover:file:bg-slate-800">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Mô tả</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#0f172a] text-[#4ade80] rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800">Lưu sân</button>
                <a href="{{ route('owner.fields.index') }}" class="px-6 py-2.5 border border-[#e2e8f0] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50">Huỷ</a>
            </div>
        </form>
    </div>
</x-app-layout>
