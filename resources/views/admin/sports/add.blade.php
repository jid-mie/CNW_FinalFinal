@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen flex flex-col items-center justify-start pt-12">
    
    <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden animate-in fade-in duration-200">
        
        <div class="bg-[#111625] px-6 py-5 text-white flex justify-between items-center">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-[#52ffa2]">Hệ thống cấu hình</h3>
                <h1 class="text-base font-bold text-slate-200 mt-0.5">Thêm môn thể thao mới</h1>
            </div>
            <a href="{{ route('admin.sports.index') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center space-x-1">
                <span>↩</span> <span>Quay lại danh sách</span>
            </a>
        </div>

        <form action="{{ route('admin.sports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
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

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tên môn thể thao *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Cầu lông, Pickleball, Bóng rổ..." class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 font-semibold text-slate-700 shadow-sm" required>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ảnh đại diện môn thể thao</label>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <input type="file" name="image_file" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-[#0f172a] file:text-white hover:file:bg-slate-800 file:transition file:cursor-pointer">
                    </div>
                    

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mô tả đặc trưng hệ thống</label>
                <textarea name="description" rows="4" placeholder="Nhập thông tin mô tả tóm tắt về quy định hoặc cơ sở vật chất đặc trưng của bộ môn..." class="w-full p-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-slate-800 text-slate-600 font-medium shadow-sm resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 mt-2">
                <a href="{{ route('admin.sports.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-5 py-3 rounded-xl transition text-center min-w-[100px]">
                    HỦY BỎ
                </a>
                <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-6 py-3 rounded-xl transition shadow-md tracking-wider uppercase">
                    THÊM MỚI NGAY
                </button>
            </div>
        </form>
    </div>
</div>
@endsection