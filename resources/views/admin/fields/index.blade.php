
@extends('layouts.app', ['hideTopbarSearch' => true])

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">
    
    <div class="mb-6">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> <span class="text-slate-600 font-semibold underline cursor-pointer">Tất cả sân thể thao</span>
        </div>
    </div>

    <div class="mb-6">
        <div class="flex items-center space-x-3">
            <h1 class="text-xl font-bold text-[#0f172a] tracking-tight uppercase">Tất cả sân thể thao</h1>
            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                {{ $totalFieldsCount }} SÂN
            </span>
        </div>
        <p class="text-xs text-slate-400 mt-1 font-medium">Quản lý danh sách và trạng thái vận hành của toàn bộ cơ sở vật chất.</p>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4 mb-6">
        
        <div class="flex-1">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tìm kiếm tên sân hoặc mã sân...</label>
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="VD: Sân Mỹ Đình, SBD-001, TN-005..." 
                       class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none text-slate-700 font-medium">
                <span class="absolute left-3.5 top-2.5 text-slate-400 text-xs">🔍</span>
            </div>
        </div>

        <div class="w-52">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Môn thể thao</label>
            <select name="sport_id" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none bg-white font-semibold text-slate-600 shadow-sm">
                <option value="">Tất cả môn học</option>
                @foreach($sports as $sport)
                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                        {{ $sport->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-52">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái vận hành</label>
            <select name="status" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none bg-white font-semibold text-slate-600 shadow-sm">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Đang bảo trì</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
            </select>
        </div>

        <div class="pt-5">
            <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-5 py-2 rounded-xl transition flex items-center space-x-2 shadow-sm">
                <span>🎛️</span> <span>Lọc dữ liệu</span>
            </button>
            @if(request()->filled('search') || request()->filled('sport_id') || request()->filled('status'))
                <a href="{{ url()->current() }}" class="ml-2 text-xs text-red-500 hover:underline font-semibold">Xóa lọc</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-4 w-28">Mã sân</th>
                    <th class="p-4">Tên sân</th>
                    <th class="p-4">Chủ sân</th>
                    <th class="p-4">Môn thể thao</th>
                    <th class="p-4">Địa chỉ</th>
                    <th class="p-4">Giá/Giờ</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100 font-medium">
                @forelse($fields as $field)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="p-4 font-mono text-slate-400 font-semibold">{{ $field->code }}</td>
                    <td class="p-4 font-bold text-slate-800">{{ $field->name }}</td>
                    <td class="p-4 text-slate-500 font-semibold">{{ $field->owner->name ?? 'N/A' }}</td>
                    <td class="p-4 text-slate-700">
                        @if(Str::contains(Str::lower($field->sport->name ?? ''), 'bóng đá')) ⚽ 
                        @elseif(Str::contains(Str::lower($field->sport->name ?? ''), 'tennis')) 🎾 
                        @elseif(Str::contains(Str::lower($field->sport->name ?? ''), 'cầu lông')) 🏸 
                        @else 🏀 @endif 
                        <span class="ml-1">{{ $field->sport->name ?? 'Chưa rõ' }}</span>
                    </td>
                    <td class="p-4 text-slate-500 font-light">{{ $field->address }}</td>
                    <td class="p-4 font-black text-slate-800">{{ number_format($field->price_per_hour) }}đ</td>
                    <td class="p-4">
                        @if($field->status === 'active')
                            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-100 inline-block tracking-wider">ĐANG HOẠT ĐỘNG</span>
                        @elseif($field->status === 'maintenance')
                            <span class="bg-[#eff6ff] text-[#3b82f6] text-[10px] font-black px-2.5 py-1 rounded-full border border-blue-100 inline-block tracking-wider">ĐANG BẢO TRÌ</span>
                        @else
                            <span class="bg-[#fef2f2] text-[#ef4444] text-[10px] font-black px-2.5 py-1 rounded-full border border-red-100 inline-block tracking-wider">TẠM DỪNG</span>
                        @endif
                    </td>
                    <td class="p-4 text-center py-4">
                        <div class="flex items-center justify-center space-x-2">
                            
                            <a href="{{ url('/admin/fields/' . $field->id . '/edit') }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100/80 border border-blue-100 px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1 font-bold text-[10px] shadow-sm tracking-wide">
                                <span>✏️</span> <span>SỬA</span>
                            </a>
                            
                            <form action="{{ url('/admin/fields/' . $field->id . '/delete') }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sân [{{ $field->name }}] không?');">
                                @csrf
                                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100/80 border border-red-100 px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1 font-bold text-[10px] shadow-sm tracking-wide">
                                    <span>🗑️</span> <span>XÓA</span>
                                </button>
                            </form>
                            
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-slate-400 font-light">Không tìm thấy sân thể thao nào khớp với bộ lọc của bạn.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center text-xs text-slate-400 font-semibold">
            <div>Hiển thị {{ $fields->count() }} trên tổng số {{ $fields->total() }} sân thể thao</div>
            <div class="flex items-center space-x-3">
                <span>Trang {{ $fields->currentPage() }} / {{ $fields->lastPage() }}</span>
                <div class="flex space-x-1">
                    <a href="{{ $fields->previousPageUrl() }}" class="px-2 py-1 border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-50 transition {{ $fields->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">&lt;</a>
                    <a href="{{ $fields->nextPageUrl() }}" class="px-2 py-1 border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-50 transition {{ !$fields->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">&gt;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#111625] p-5 rounded-2xl text-white shadow-md relative overflow-hidden flex flex-col justify-between h-28">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tỷ lệ lấp đầy</span>
            <div class="flex items-baseline space-x-2 mt-1 z-10">
                <span class="text-2xl font-black">{{ $occupancyRate }}%</span>
                <span class="text-[#10b981] text-[10px] font-bold">📈 +12% so với tháng trước</span>
            </div>
            <div class="absolute bottom-0 right-0 left-0 opacity-10 h-10 bg-gradient-to-t from-emerald-500 to-transparent"></div>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center h-28">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sân đang bảo trì</span>
                <span class="text-2xl font-black text-slate-800 mt-1 block">{{ str_pad($maintenanceCount, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-slate-400 text-[10px] mt-1 block font-light">Dự kiến hoàn thành trong 48h tới</span>
            </div>
            <span class="text-xl bg-blue-50/80 p-3 rounded-xl text-blue-500 shadow-sm">🛠️</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-28">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Doanh thu trung bình</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $averageRevenueStr }} <span class="text-xs text-slate-400 font-normal">/ Sân / Ngày</span></span>
            <span class="text-emerald-500 text-[10px] font-bold mt-1 block flex items-center space-x-1">
                <span>💵</span> <span>Đã kiểm toán hệ thống</span>
            </span>
        </div>
    </div>

</div>
@endsection

