<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold font-heading text-2xl text-[#0f172a] uppercase tracking-wide">Khách Hàng</h2>
    </x-slot>
    <div class="py-6">
        <div class="bg-white rounded-xl border border-[#e2e8f0] p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, email, SĐT..." class="w-full px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm focus:outline-none focus:border-[#0f172a]">
                </div>
                <div>
                    <select name="sort_field" class="px-3 py-2 border border-[#e2e8f0] rounded-lg text-sm">
                        <option value="bookings_count" {{ request('sort_field') == 'bookings_count' ? 'selected' : '' }}>Nhiều lượt nhất</option>
                        <option value="total_spent" {{ request('sort_field') == 'total_spent' ? 'selected' : '' }}>Chi nhiều nhất</option>
                        <option value="name" {{ request('sort_field') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0f172a] text-white rounded-lg text-xs font-bold uppercase tracking-wider">Tìm</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($customers as $c)
            <a href="{{ route('owner.customers.show', $c) }}" class="block bg-white rounded-xl border border-[#e2e8f0] p-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-[#0f172a] text-[#4ade80] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($c->name, 0, 2)) }}</span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-sm text-[#0f172a] truncate">{{ $c->name }}</h3>
                        <p class="text-xs text-[#45464d] truncate">{{ $c->email ?? $c->phone }}</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="text-[#45464d]">{{ $c->bookings_count ?? 0 }} lượt đặt</span>
                    <span class="font-bold text-[#0f172a]">{{ number_format($c->total_spent ?? 0, 0, ',', '.') }}đ</span>
                </div>
            </a>
            @endforeach
        </div>

        @if($customers->count() == 0)
            <p class="text-center py-8 text-sm text-[#45464d]">Chưa có khách hàng nào</p>
        @endif
        <div class="mt-4">{{ $customers->links() }}</div>
    </div>
</x-app-layout>
