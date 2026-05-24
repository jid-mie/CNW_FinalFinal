<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Quản Lý Sân</h2>
            <a href="{{ route('owner.fields.create') }}" class="bg-[#0f172a] text-[#4ade80] px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition-colors">+ Thêm sân</a>
        </div>
    </x-slot>
    <div class="py-6">
        <!-- Search & Filters -->
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên sân, địa chỉ..." class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Môn thể thao</label>
                    <select name="sport_id" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="">Tất cả</option>
                        @foreach($sports as $s)
                            <option value="{{ $s->id }}" {{ request('sport_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Trạng thái</label>
                    <select name="status" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#45464d] uppercase tracking-wider block mb-1">Sắp xếp</label>
                    <select name="sort_field" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                        <option value="created_at" {{ request('sort_field') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="name" {{ request('sort_field') == 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="price_per_hour" {{ request('sort_field') == 'price_per_hour' ? 'selected' : '' }}>Giá</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0f172a] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800">Lọc</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif

        <!-- Fields Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fields as $field)
            <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-36 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative">
                    @if($field->image_url)
                        <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    @endif
                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $field->status == 'active' ? 'bg-[#4ade80] text-[#0f172a]' : 'bg-gray-300 text-gray-700' }}">
                        {{ $field->status == 'active' ? 'Đang mở' : 'Tạm ngưng' }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-[#0f172a]">{{ $field->name }}</h3>
                            <span class="text-xs text-[#45464d]">{{ $field->sport?->name }}</span>
                        </div>
                        <span class="font-bold text-sm text-[#0f172a]">{{ number_format($field->price_per_hour, 0, ',', '.') }}đ/h</span>
                    </div>
                    @if($field->address)
                        <p class="text-xs text-[#45464d] mb-3 truncate">{{ $field->address }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-[#45464d] mb-3">
                        <span>{{ $field->time_slots_count ?? 0 }} khung giờ</span>
                        <span>|</span>
                        <span>{{ $field->bookings_count ?? 0 }} đặt lịch</span>
                    </div>
                    <div class="flex items-center gap-2 pt-3 border-t border-[#e2e8f0]">
                        <a href="{{ route('owner.time-slots.index', $field) }}" class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50">Giờ</a>
                        <a href="{{ route('owner.fields.edit', $field) }}" class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50">Sửa</a>
                        <form action="{{ route('owner.fields.toggle-status', $field) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-[#e2e8f0] rounded-lg hover:bg-slate-50">
                                {{ $field->status == 'active' ? 'Tắt' : 'Bật' }}
                            </button>
                        </form>
                        <form action="{{ route('owner.fields.destroy', $field) }}" method="POST" class="inline" onsubmit="return confirm('Xoá sân này?')">
                            @csrf @method('DELETE')
                            <button class="px-2 py-1 text-xs font-bold uppercase tracking-wider border border-red-200 text-red-700 rounded-lg hover:bg-red-50">Xoá</button>
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