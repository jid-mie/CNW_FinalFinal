@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#f8fafc] min-h-screen">

    <div class="flex justify-between items-center mb-4">
        <div class="text-xs text-slate-400 font-medium">
            <span>Admin</span> <span class="mx-1.5 text-slate-300">&gt;</span> <span class="text-slate-600 font-semibold underline cursor-pointer">Quản lý môn thể thao</span>
        </div>
    </div>

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight uppercase">Quản lý môn thể thao</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">Danh sách các môn thể thao được hỗ trợ vận hành trên toàn bộ hệ thống.</p>
        </div>
        
        <a href="{{ route('admin.sports.add') }}" class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl flex items-center space-x-2 transition shadow-sm tracking-wider uppercase inline-block">
            <span>➕</span> <span>Thêm môn thể thao mới</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            // Kho ảnh CDN thể thao mới 100%, độ ổn định cao, chống kẹt tải từ Unsplash
            $sportImages = [
                'bong-da'     => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&auto=format&fit=crop&q=80',
                'bong-chuyen' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=600&auto=format&fit=crop&q=80',
                'bong-ro'     => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=600&auto=format&fit=crop&q=80',
                'cau-long'    => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=600&auto=format&fit=crop&q=80',
                'tennis'      => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?w=600&auto=format&fit=crop&q=80',
                'bong-ban'    => 'https://images.unsplash.com/photo-1534158914592-062992fbe900?w=600&auto=format&fit=crop&q=80',
                'pickleball'  => 'https://images.unsplash.com/photo-1613918108466-292b78a8ef95?w=600&auto=format&fit=crop&q=80',
                'da-cau'      => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600&auto=format&fit=crop&q=80',
            ];
        @endphp

        @forelse($sports as $sport)
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-md transition duration-200">
            
            <div class="w-full h-44 bg-slate-100 relative overflow-hidden">
                @php
                    $cleanUrl = isset($sport->image_url) ? trim($sport->image_url) : '';
                    $cleanSlug = isset($sport->slug) ? strtolower(trim($sport->slug)) : '';
                    
                    $fallbackImage = $sportImages[$cleanSlug] ?? 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600';
                    
                    if (!empty($cleanUrl)) {
                        $imgSrc = (str_starts_with($cleanUrl, 'http://') || str_starts_with($cleanUrl, 'https://')) 
                            ? $cleanUrl 
                            : asset($cleanUrl);
                    } else {
                        $imgSrc = $fallbackImage;
                    }
                @endphp

                <img src="{{ $imgSrc }}" 
                     alt="{{ $sport->name }}" 
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600';"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between">
                <div class="mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">{{ $sport->name }}</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5 font-mono">Slug: <span class="text-red-500 font-semibold">{{ $sport->slug }}</span></p>
                        </div>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $sport->is_active ? 'checked' : '' }} onchange="toggleSportStatus({{ $sport->id }})">
                            <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 line-clamp-2 font-medium leading-relaxed">{{ $sport->description ?? 'Chưa có mô tả chi tiết cho bộ môn này.' }}</p>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs font-bold mt-auto">
                    <span id="status-badge-{{ $sport->id }}" class="{{ $sport->is_active ? 'text-emerald-500' : 'text-slate-400' }} flex items-center gap-1.5 text-[11px] uppercase tracking-wider">
                        <span class="status-dot w-1.5 h-1.5 {{ $sport->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }} rounded-full"></span> 
                        <span class="status-text">{{ $sport->is_active ? 'Đang hoạt động' : 'Tạm dừng' }}</span>
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.sports.edit', $sport->id) }}" class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 px-2.5 py-1 rounded-lg border border-transparent hover:border-blue-100 transition flex items-center gap-1">
                            ✏️ Sửa
                        </a>
                        <form action="{{ route('admin.sports.destroy', $sport->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa môn thể thao này?')">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-red-600 hover:bg-red-50 px-2.5 py-1 rounded-lg border border-transparent hover:border-red-100 transition flex items-center gap-1">
                                🗑️ Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-3 bg-white p-12 text-center rounded-2xl border border-slate-200 text-slate-400 font-light">
            Không tìm thấy bộ môn thể thao nào trong hệ thống.
        </div>
        @endforelse
    </div>
</div>

<script>
    function toggleSportStatus(id) {
        const badge = document.getElementById(`status-badge-${id}`);
        const dot = badge.querySelector('.status-dot');
        const text = badge.querySelector('.status-text');

        fetch(`{{ url('admin/sports') }}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                if (data.is_active) {
                    badge.className = 'text-emerald-500 flex items-center gap-1.5 text-[11px] uppercase tracking-wider';
                    dot.className = 'status-dot w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse';
                    text.textContent = 'Đang hoạt động';
                    showToast('Đã kích hoạt bộ môn hoạt động thành công!', 'success');
                } else {
                    badge.className = 'text-slate-400 flex items-center gap-1.5 text-[11px] uppercase tracking-wider';
                    dot.className = 'status-dot w-1.5 h-1.5 bg-slate-400 rounded-full';
                    text.textContent = 'Tạm dừng';
                    showToast('Đã tạm ngưng hoạt động bộ môn!', 'info');
                }
            } else {
                showToast('Cập nhật trạng thái thất bại!', 'error');
            }
        })
        .catch(err => {
            showToast('Cập nhật trạng thái thất bại!', 'error');
        });
    }

    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-3';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = 'flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wider text-slate-800 transition-all duration-300 transform translate-y-10 opacity-0';
        
        if (type === 'success') {
            toast.className += ' bg-emerald-50 border-emerald-200 text-emerald-800';
            toast.innerHTML = `<span>🟢</span> <span>${message}</span>`;
        } else if (type === 'info') {
            toast.className += ' bg-amber-50 border-amber-200 text-amber-800';
            toast.innerHTML = `<span>🟡</span> <span>${message}</span>`;
        } else {
            toast.className += ' bg-red-50 border-red-200 text-red-800';
            toast.innerHTML = `<span>🔴</span> <span>${message}</span>`;
        }
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection