<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">
                {{ __('Thêm Tài khoản Mới') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-white text-slate-700 border border-[#e2e8f0] text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white overflow-hidden shadow-sm rounded-xl border border-[#e2e8f0] transition-all">
            <div class="p-6 border-b border-[#e2e8f0] bg-slate-50">
                <h3 class="text-lg font-bold font-heading text-[#0f172a] uppercase tracking-wide">Thông tin tài khoản</h3>
                <p class="text-xs text-[#45464d] mt-1">Vui lòng điền đầy đủ các thông tin bắt buộc dưới đây để thêm tài khoản mới.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                @include('users.partials.form')

                <div class="pt-6 border-t border-[#e2e8f0] flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-slate-700 border border-[#e2e8f0] text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-[#0f172a] text-[#4ade80] text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
