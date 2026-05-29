@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">
    
    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> <span class="text-slate-600 font-semibold underline cursor-pointer">Tất cả sân thể thao</span>
        </div>
    </div>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800 tracking-tight uppercase flex items-center gap-2">
            Tất cả sân thể thao 
            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-100">
                {{ $fields->total() }} SÂN
            </span>
        </h1>
        <p class="text-xs text-slate-400 mt-1 font-medium">Quản lý danh sách và trạng thái vận hành của toàn bộ cơ sở vật chất.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="bg-[#1e2538] p-5 rounded-2xl text-white shadow-sm flex flex-col justify-between h-28 relative overflow-hidden">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block opacity-70">Tỷ lệ lấp đầy</span>
                <h3 class="text-3xl font-black mt-1 font-sans tracking-tight">{{ $occupancyRate }}%</h3>
            </div>
            <span class="text-[10px] text-[#3cd882] font-medium flex items-center gap-1">📈 Dữ liệu thời gian thực</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between h-28">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Sân đang bảo trì</span>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 font-sans">{{ str_pad($maintenanceCount, 2, '0', STR_PAD_LEFT) }}</h3>
                </div>
                <span class="text-[10px] text-slate-400 font-medium">Cơ sở đang dừng đón khách</span>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-lg border border-blue-100 shadow-sm">🛠️</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between h-28">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Doanh thu trung bình</span>
                <h3 class="text-3xl font-black text-slate-800 mt-1 font-sans tracking-tight">{{ $avgRevenue }}<span class="text-xs font-bold text-slate-400 ml-1">/Sân/Ngày</span></h3>
            </div>
            <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100 w-fit">💵 Đã kiểm toán dòng tiền</span>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.fields.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tìm kiếm tên hoặc mã sân...</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="VD: Sân Mỹ Đình, SBD-001..." class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none text-slate-700">
                <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Môn thể thao</label>
            <select name="sport_id" class="w-full p-2 border border-slate-200 rounded-xl text-xs bg-white text-slate-600 font-semibold focus:outline-none">
                <option value="">Tất cả bộ môn</option>
                @foreach($sports as $sport)
                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái vận hành</label>
            <div class="flex space-x-2">
                <select name="status" class="w-full p-2 border border-slate-200 rounded-xl text-xs bg-white text-slate-600 font-semibold focus:outline-none flex-1">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                </select>
                <button type="submit" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-4 rounded-xl transition shadow-sm flex items-center space-x-1">
                    <span>⚙️</span> <span>Lọc</span>
                </button>
                @if(request()->anyFilled(['search', 'sport_id', 'status']))
                    <a href="{{ route('admin.fields.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 rounded-xl transition border border-slate-200/50 flex items-center justify-center">Xóa</a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
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
                    <td class="p-4 font-bold font-mono text-xs uppercase">
                        <a href="{{ route('admin.fields.show', $field->id) }}" class="text-[#0f172a] hover:text-[#3cd882] transition-colors">
                            {{ $field->code ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="p-4 font-bold">
                        <a href="{{ route('admin.fields.show', $field->id) }}" class="text-slate-800 hover:text-[#3cd882] transition-colors">
                            {{ $field->name }}
                        </a>
                    </td>
                    <td class="p-4 text-slate-500 font-semibold">{{ $field->owner->name ?? 'N/A' }}</td>
                    <td class="p-4 text-slate-500 font-semibold">
                        @if($field->sport && $field->sport->slug === 'bong-da') ⚽ 
                        @elseif($field->sport && $field->sport->slug === 'cau-long') 🏸 
                        @elseif($field->sport && $field->sport->slug === 'pickleball') 🏓 
                        @elseif($field->sport && $field->sport->slug === 'tennis') 🎾 
                        @else 🏆 @endif
                        {{ $field->sport->name ?? 'N/A' }}
                    </td>
                    <td class="p-4 text-slate-400 max-w-xs truncate">{{ $field->address }}</td>
                    <td class="p-4 font-black text-slate-800 font-mono">{{ number_format($field->price_per_hour) }}đ</td>
                    <td class="p-4">
                        @if($field->status === 'active')
                            <span class="bg-[#e6fbf1] text-[#10b981] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-100 tracking-wider">ĐANG HOẠT ĐỘNG</span>
                        @else
                            <span class="bg-red-50 text-red-600 text-[10px] font-black px-2.5 py-1 rounded-full border border-red-100 tracking-wider">BẢO TRÌ</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2 text-xs font-bold">
                        <a href="{{ route('admin.fields.edit', $field->id) }}" class="text-blue-500 hover:bg-blue-50 px-2 py-1 rounded-lg border border-blue-100 transition">✏️ SỬA</a>
                        <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa sân này?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:bg-red-50 px-2 py-1 rounded-lg border border-red-100 transition">🗑️ XÓA</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-slate-400 font-light">Không tìm thấy sân thể thao nào phù hợp.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-slate-50 border-t border-slate-200">
            {{ $fields->links() }}
        </div>
    </div>
</div>
@endsection