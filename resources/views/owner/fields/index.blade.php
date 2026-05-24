<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                Quản Lý Sân
            </h2>
            <a href="{{ route('owner.fields.create') }}" class="bg-[#0f172a] text-[#4ade80] px-4 py-2.5 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Thêm sân mới
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Search & Filters -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[240px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên sân, địa chỉ..." class="w-full px-3.5 py-2 border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a]/10 rounded-lg text-sm focus:outline-none focus:border-[#0f172a] transition-all">
                </div>
                <div class="w-full sm:w-auto min-w-[150px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Môn thể thao</label>
                    <select name="sport_id" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="">Tất cả</option>
                        @foreach($sports as $s)
                            <option value="{{ $s->id }}" {{ request('sport_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto min-w-[130px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Trạng thái</label>
                    <select name="status" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto min-w-[130px]">
                    <label class="text-[11px] font-bold text-[#45464d] uppercase tracking-wider block mb-1.5">Sắp xếp</label>
                    <select name="sort_field" class="w-full px-3.5 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a] focus:ring-2 focus:ring-[#0f172a]/10 transition-all">
                        <option value="created_at" {{ request('sort_field') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="name" {{ request('sort_field') == 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="price_per_hour" {{ request('sort_field') == 'price_per_hour' ? 'selected' : '' }}>Giá</option>
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-[#0f172a] text-[#4ade80] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm">
                    Lọc kết quả
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-[#4ade80]/40 text-[#0f172a] px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Fields Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fields as $field)
            <div class="group bg-white rounded-xl border border-[#e2e8f0] overflow-hidden hover:shadow-md transition-all duration-200">
                <!-- 16:9 Image -->
                <div class="aspect-video w-full bg-slate-50 relative overflow-hidden">
                    @if($field->image_url)
                        <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    @endif
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $field->status == 'active' ? 'bg-[#4ade80] text-[#0f172a]' : 'bg-gray-300 text-gray-700' }}">
                        {{ $field->status == 'active' ? 'Đang mở' : 'Tạm ngưng' }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <h3 class="font-bold text-md text-[#0f172a] truncate">{{ $field->name }}</h3>
                            <span class="inline-block mt-0.5 text-xs text-[#45464d] bg-slate-50 border border-[#e2e8f0] px-2 py-0.5 rounded">{{ $field->sport?->name }}</span>
                        </div>
                        <span class="font-bold text-sm text-[#0f172a] whitespace-nowrap bg-slate-50 px-2 py-1 rounded border border-[#e2e8f0]">{{ number_format($field->price_per_hour, 0, ',', '.') }}đ/h</span>
                    </div>
                    @if($field->address)
                        <p class="text-xs text-[#45464d] mb-4 truncate flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $field->address }}
                        </p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-[#45464d] mb-4">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $field->time_slots_count ?? 0 }} khung giờ
                        </span>
                        <span>|</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            {{ $field->bookings_count ?? 0 }} đặt lịch
                        </span>
                    </div>
                    <div class="flex items-center gap-2 pt-4 border-t border-[#e2e8f0]">
                        <a href="{{ route('owner.time-slots.index', $field) }}" class="flex-1 text-center py-2 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-[#0f172a] hover:text-[#4ade80] hover:border-[#0f172a] transition-all duration-200">Giờ chơi</a>
                        <a href="{{ route('owner.fields.edit', $field) }}" class="px-3 py-2 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50 transition-colors">Sửa</a>
                        <form action="{{ route('owner.fields.toggle-status', $field) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-2 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50 transition-colors">
                                {{ $field->status == 'active' ? 'Tạm dừng' : 'Kích hoạt' }}
                            </button>
                        </form>
                        <form action="{{ route('owner.fields.destroy', $field) }}" method="POST" class="inline" onsubmit="return confirm('Xoá sân này?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-2 text-xs font-bold uppercase tracking-wider border border-red-200 text-red-700 rounded-lg hover:bg-red-50 transition-colors">Xoá</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $fields->links() }}
        </div>
    </div>
</x-app-layout>