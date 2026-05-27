@extends('layouts.app')

@section('content')
<div x-data="{ 
    openModal: false, 
    openEditModal: false, 
    editSport: { id: '', name: '', description: '' } 
}">
    
    <div class="mb-6">
        <nav class="text-xs text-slate-400 mb-1 flex items-center space-x-2">
            <span>Admin</span>
            <span>&gt;</span>
            <span class="text-slate-600 font-medium">Quản lý môn thể thao</span>
        </nav>
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Quản lý môn thể thao</h2>
                <p class="text-sm text-slate-400 mt-0.5 font-light">Danh sách các môn thể thao được hỗ trợ trên hệ thống.</p>
            </div>
            <button @click="openModal = true" class="bg-[#1e2530] hover:bg-slate-800 text-[#52ffa2] px-4 py-2 rounded-lg text-xs font-medium inline-flex items-center space-x-2 transition shadow-sm">
                <svg class="w-4 h-4 text-[#52ffa2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                </svg>
                <span>Thêm môn thể thao mới</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sports as $sport)
        <div x-data="{ 
                id: {{ $sport->id }}, 
                name: '{{ $sport->name }}', 
                active: {{ $sport->is_active ? 'true' : 'false' }} 
             }" 
             x-init="$watch('active', value => {
                fetch(`/admin/sports/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_active: value })
                })
             })"
             /* 🌟 ĐÃ SỬA: Kiểm tra an toàn, chống crash Alpine kể cả khi không tìm thấy ô Search */
             x-show="typeof $store.search !== 'undefined' && $store.search.query ? name.toLowerCase().includes($store.search.query.toLowerCase()) : true"
             class="bg-white rounded-xl border border-slate-200/80 overflow-hidden flex flex-col justify-between shadow-sm" x-transition>
            
            <div>
                <div class="relative">
                    <img src="{{ $sport->image }}" alt="{{ $sport->name }}" class="w-full h-44 object-cover">
                    @if($sport->badge)
                    <span class="absolute bottom-3 left-3 bg-[#10b981] text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded">{{ $sport->badge }}</span>
                    @endif
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-base text-slate-800" x-text="name"></h3>
                            <span class="text-xs text-slate-400 block mt-0.5">Slug: <code class="bg-slate-50 px-1 rounded text-rose-500 font-mono text-[11px]">{{ $sport->slug }}</code></span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="active" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#10b981]"></div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mt-3 line-clamp-3 leading-relaxed">{{ $sport->description }}</p>
                </div>
            </div>
            
            <div class="px-5 py-3 border-t border-slate-100 flex justify-between items-center bg-slate-50/40">
                <span class="text-xs font-medium" :class="active ? 'text-emerald-600' : 'text-slate-400'" x-text="active ? 'Đang hoạt động' : 'Tạm dừng'"></span>
                <div class="flex items-center space-x-4">
                    <button @click="openEditModal = true; editSport = { id: {{ $sport->id }}, name: '{{ addslashes($sport->name) }}', description: '{{ addslashes($sport->description) }}' }" class="text-slate-400 hover:text-blue-500 transition flex items-center space-x-1 text-xs">
                        <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Sửa</span>
                    </button>
                    
                    <form action="/admin/sports/{{ $sport->id }}/delete" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?')" class="inline m-0 p-0">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition flex items-center space-x-1 text-xs">
                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Xóa</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @include('admin.sports.add')
    @include('admin.sports.edit')

</div>
@endsection