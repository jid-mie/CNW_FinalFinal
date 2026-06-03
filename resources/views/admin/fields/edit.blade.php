@extends('layouts.app', ['hideTopbarSearch' => true])

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen flex flex-col items-center justify-start pt-12">
    
    <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        
        <div class="bg-[#111625] px-6 py-5 text-white flex justify-between items-center">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-[#52ffa2]">Cập nhật thông tin sân</h3>
                <h1 class="text-base font-bold text-slate-200 mt-0.5">Cơ sở vật chất hệ thống</h1>
            </div>
            <a href="{{ url('/admin/fields') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center space-x-1">
                <span>↩</span> <span>Quay lại danh sách</span>
            </a>
        </div>

        <form action="{{ route('admin.fields.update', $field->id) }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                    <span class="text-xs font-black text-red-700 uppercase tracking-wider block mb-1">⚠️ Lỗi nhập liệu từ hệ thống:</span>
                    <ul class="list-disc pl-4 text-xs text-red-600 font-bold space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mã sân</label>
                    <input type="text" name="code" value="{{ old('code', $field->code) }}" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-mono font-bold text-emerald-600 bg-slate-50 shadow-sm" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tên định danh sân thể thao</label>
                    <input type="text" name="name" value="{{ old('name', $field->name) }}" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-semibold text-slate-700 shadow-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Môn thể thao</label>
                    <select name="sport_id" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none bg-white font-semibold text-slate-600 shadow-sm" required>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->id }}" {{ $field->sport_id == $sport->id ? 'selected' : '' }}>
                                {{ $sport->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Chủ quản lý sân</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $field->owner->name ?? '') }}" placeholder="Nhập họ và tên chủ sân..." class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-semibold text-slate-700 shadow-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Đơn giá thuê (đ / Giờ)</label>
                    <input type="number" name="price_per_hour" value="{{ old('price_per_hour', $field->price_per_hour) }}" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-mono font-bold text-slate-800 shadow-sm" required>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái vận hành</label>
                    <select name="status" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none bg-white font-semibold text-slate-600 shadow-sm" required>
                        <option value="active" {{ $field->status == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="maintenance" {{ $field->status == 'maintenance' ? 'selected' : '' }}>Đang bảo trì</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Địa chỉ vị trí cơ sở vật chất</label>
                <input type="text" name="address" value="{{ old('address', $field->address) }}" class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-medium text-slate-600 shadow-sm" required>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 mt-2">
                <a href="{{ url('/admin/fields') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-5 py-3 rounded-xl transition text-center min-w-[100px]">
                    HỦY BỎ
                </a>
                <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-6 py-3 rounded-xl transition shadow-md tracking-wider uppercase">
                    LƯU THAY ĐỔI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection