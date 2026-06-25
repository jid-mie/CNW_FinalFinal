<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading font-extrabold text-3xl text-slate-900 uppercase tracking-tight">
                    {{ __('Đặt Sân Đấu') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Tạo lịch đặt sân nhanh chóng theo các bước</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 px-3.5 py-2 rounded-xl transition-all border border-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="bookingForm()">
        @if (session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-950 rounded-r-xl p-4 text-sm font-semibold flex items-center gap-3 shadow-sm transition-all duration-300">
                <div class="p-1.5 bg-rose-500/20 text-rose-800 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Step Progress -->
        <div class="mb-10 max-w-xl mx-auto px-4">
            <div class="flex items-center justify-between relative">
                <!-- Progress Line Background -->
                <div class="absolute left-4 right-4 top-5 h-0.5 bg-slate-200 -z-10"></div>
                <!-- Active Progress Line -->
                <div class="absolute left-4 top-5 h-0.5 bg-slate-900 transition-all duration-500 -z-10"
                     :style="'width: ' + ((currentStep - 1) / (steps.length - 1) * 100) + '%'"></div>

                <template x-for="(step, i) in steps" :key="i">
                    <div class="flex flex-col items-center">
                        <div :class="currentStep >= i+1 ? 'bg-slate-900 text-[#3cd882] ring-4 ring-slate-100 scale-110' : 'bg-slate-200 text-slate-500 ring-4 ring-white'" 
                             class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black transition-all duration-500 z-10">
                            <span x-text="i+1"></span>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider mt-3" 
                              :class="currentStep >= i+1 ? 'text-slate-900' : 'text-slate-400'" 
                              x-text="step"></span>
                    </div>
                </template>
            </div>
        </div>

        <form action="{{ route('customer.bookings.store') }}" method="POST" id="booking-form">
            @csrf
            <input type="hidden" name="field_id" x-model="selectedField">
            <input type="hidden" name="time_slot_id" x-model="selectedSlot">
            <input type="hidden" name="booking_date" x-model="selectedDate">

            <!-- Step 1: Chọn môn thể thao -->
            <div x-show="currentStep === 1" class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm" x-transition>
                <div class="mb-6">
                    <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Bước 1: Chọn môn thể thao</h3>
                    <p class="text-xs text-slate-500 mt-1">Lựa chọn môn thể thao bạn muốn đặt lịch thi đấu.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($sports as $sport)
                            <div @click="selectSport({{ $sport->id }})"
                             :class="selectedSport === {{ $sport->id }} ? 'border-[#3cd882] bg-emerald-50/20 ring-4 ring-emerald-500/10' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50/30'"
                             class="relative border-2 rounded-2xl p-6 text-center cursor-pointer transition-all duration-300 hover:shadow-md group">
                            
                            <!-- Selection badge -->
                            <div x-show="selectedSport === {{ $sport->id }}" 
                                 class="absolute top-2.5 right-2.5 bg-[#3cd882] text-slate-900 rounded-full p-1"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-75"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>

                            @if ($sport->image_url)
                                <img src="{{ asset($sport->image_url) }}" alt="{{ $sport->name }}" class="w-20 h-20 mx-auto mb-3 object-contain transition-transform duration-300 group-hover:scale-110 filter drop-shadow-[0_8px_16px_rgba(0,0,0,0.15)]">
                            @elseif ($sport->icon)
                                <img src="{{ $sport->icon }}" alt="{{ $sport->name }}" class="w-14 h-14 mx-auto mb-3 object-contain transition-transform duration-300 group-hover:scale-110">
                            @else
                                <div class="w-14 h-14 mx-auto mb-3 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#3cd882]/10 group-hover:text-[#3cd882]">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                            <span class="block text-sm font-extrabold text-slate-900 group-hover:text-slate-950 uppercase tracking-wider" x-text="'{{ $sport->name }}'"></span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 2: Chọn sân -->
            <div x-show="currentStep === 2" class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm" x-transition>
                <div class="mb-6">
                    <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Bước 2: Lựa chọn sân đấu</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Danh sách các sân thi đấu có sẵn tương ứng với bộ môn của bạn.</p>
                </div>

                <div x-show="loading" class="text-center py-12">
                    <svg class="animate-spin h-8 w-8 text-slate-900 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="mt-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Đang tải danh sách sân...</p>
                </div>
                
                <div x-show="!loading && fields.length === 0" class="text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                    <p class="text-sm font-bold text-slate-900">Không có sân nào phù hợp</p>
                    <p class="text-xs text-slate-400 mt-1">Hiện tại các sân tương ứng với bộ môn này chưa khả dụng.</p>
                </div>

                <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="field in fields" :key="field.id">
                            <div @click="selectField(field.id)"
                             :class="selectedField === field.id ? 'border-[#3cd882] bg-emerald-50/10 ring-4 ring-emerald-500/10' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50/30'"
                             class="relative border-2 rounded-2xl p-6 cursor-pointer transition-all duration-300 hover:shadow-md flex flex-col justify-between group overflow-hidden">
                            
                            <!-- Field Image Banner -->
                            <div class="relative h-44 bg-slate-100 overflow-hidden -mx-6 -mt-6 border-b border-slate-100 mb-4">
                                <template x-if="field.image_url">
                                    <img :src="'/' + field.image_url" :alt="field.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </template>
                                <template x-if="!field.image_url">
                                    <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400">
                                        <svg class="w-12 h-12 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.008-.008a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <div class="flex justify-between items-start gap-4 mb-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <!-- Field Code Badge -->
                                        <span class="inline-flex items-center text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-slate-100 text-slate-700" x-text="field.code"></span>
                                        
                                        <!-- Selected Badge -->
                                        <span x-show="selectedField === field.id" class="inline-flex items-center gap-1 text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-[#3cd882]/20 text-emerald-800">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            Đang chọn
                                        </span>
                                    </div>
                                    <h4 class="font-extrabold text-base text-slate-900 tracking-tight mt-1.5" x-text="field.name"></h4>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-100 block" x-text="formatPrice(field.price_per_hour) + ' / giờ'"></span>
                                </div>
                            </div>

                            <!-- Description & Address info -->
                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <p x-show="field.description" class="text-xs text-slate-500 leading-relaxed font-medium" x-text="field.description"></p>
                                
                                <p class="text-xs text-slate-500 font-semibold flex items-center gap-1.5 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                    <span x-text="field.address"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-start">
                    <button type="button" @click="prevStep" class="px-6 py-3 border border-slate-200 rounded-2xl font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors">Quay lại</button>
                </div>
            </div>

            <!-- Step 3: Chọn ngày + giờ -->
            <div x-show="currentStep === 3" class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-sm" x-transition>
                <div class="mb-6">
                    <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Bước 3: Chọn thời gian thi đấu</h3>
                    <p class="text-xs text-slate-500 mt-1">Lựa chọn ngày và khung giờ đấu còn trống trên hệ thống.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Left: Select Date -->
                    <div class="md:col-span-1 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Chọn ngày đấu</label>
                            <input type="date" x-model="selectedDate" @change="loadSlots()"
                                   :min="minDate" :max="maxDate"
                                   class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3 shadow-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ghi chú cho sân (Tùy chọn)</label>
                            <textarea name="note" placeholder="Ví dụ: Cần mượn áo pitch, thuê thêm trọng tài..." rows="3"
                                      class="w-full rounded-2xl border-slate-200 text-xs font-semibold text-slate-800 focus:ring-slate-900 focus:border-slate-900 py-2.5 px-3.5 shadow-xs"></textarea>
                        </div>
                    </div>

                    <!-- Right: Slots list -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Các khung giờ hoạt động</label>
                        
                        <div x-show="!selectedDate" class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-400 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                            <p class="text-xs font-bold text-slate-900 uppercase tracking-wider">Vui lòng chọn ngày đấu trước</p>
                        </div>

                        <div x-show="selectedDate && loading" class="text-center py-12">
                            <svg class="animate-spin h-8 w-8 text-slate-900 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <p class="mt-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Đang tải lịch trống...</p>
                        </div>

                        <div x-show="selectedDate && !loading">
                            <div x-show="slots.length === 0" class="text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                Không có khung giờ hoạt động nào trong ngày này.
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <template x-for="slot in slots" :key="slot.id">
                                    <div @click="slot.is_available && selectSlot(slot.id)"
                                         :class="{
                                             'border-[#3cd882] bg-emerald-50/10 ring-4 ring-emerald-500/10': selectedSlot === slot.id,
                                             'hover:border-slate-300 hover:bg-slate-50/30 cursor-pointer': slot.is_available && selectedSlot !== slot.id,
                                             'opacity-35 cursor-not-allowed bg-slate-50 border-slate-100': !slot.is_available
                                         }"
                                         class="relative border-2 border-slate-100 rounded-2xl p-4 text-center transition-all duration-300 overflow-hidden">
                                        
                                        <!-- Checkmark indicator for selected slot -->
                                        <div x-show="selectedSlot === slot.id" class="absolute top-1.5 right-1.5 text-emerald-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </div>

                                        <span class="block text-sm font-black tracking-tight" 
                                              :class="slot.is_available ? 'text-slate-900' : 'text-slate-400'" 
                                              x-text="slot.start_time + ' - ' + slot.end_time"></span>
                                        <span class="block text-[9px] mt-1.5 font-extrabold uppercase tracking-wider"
                                              :class="selectedSlot === slot.id ? 'text-emerald-800' : (slot.is_available ? 'text-emerald-600' : 'text-rose-500')"
                                              x-text="selectedSlot === slot.id ? 'Đang chọn' : (slot.is_available ? 'Còn trống' : 'Đã đặt')"></span>
                                    </div>
                                </template>
                            </div>

                            <div x-show="selectedSlot && selectedField" class="mt-8 p-5 bg-emerald-50/50 border border-emerald-100 rounded-2xl flex items-center justify-between shadow-xs transition-all duration-300">
                                <div>
                                    <span class="text-xs text-emerald-800 font-extrabold uppercase tracking-wider block">Tổng chi phí dự kiến</span>
                                    <span class="text-3xl font-black text-emerald-700 mt-1 block tracking-tight" x-text="formatPrice(selectedFieldPrice)"></span>
                                </div>
                                <div class="p-3.5 bg-[#3cd882]/10 text-emerald-700 rounded-2xl border border-[#3cd882]/20">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between">
                    <button type="button" @click="prevStep" class="px-6 py-3 border border-slate-200 rounded-2xl font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors">Quay lại</button>
                    <button type="button" @click="submitForm" :disabled="!selectedSlot || !selectedDate" 
                            class="px-8 py-3 bg-[#3cd882] text-slate-950 rounded-2xl font-black uppercase tracking-wider text-xs shadow-sm hover:shadow-md hover:bg-[#2fc473] transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        Xác nhận đặt sân
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function bookingForm() {
            return {
                steps: ['Môn thể thao', 'Chọn sân', 'Ngày & Giờ'],
                currentStep: 1,
                selectedSport: null,
                selectedField: null,
                selectedDate: '',
                selectedSlot: null,
                fields: [],
                slots: [],
                loading: false,
                minDate: new Date().toISOString().split('T')[0],
                maxDate: new Date(Date.now() + 14 * 86400000).toISOString().split('T')[0],

                get selectedFieldPrice() {
                    const f = this.fields.find(f => f.id === this.selectedField);
                    return f ? f.price_per_hour : 0;
                },

                selectSport(id) {
                    this.selectedSport = id;
                    this.selectedField = null;
                    this.selectedSlot = null;
                    this.selectedDate = '';
                    this.slots = [];
                    this.currentStep = 2;
                    this.loadFields();
                },

                async loadFields() {
                    this.loading = true;
                    try {
                        const resp = await fetch('/customer/booking/sport/' + this.selectedSport + '/fields');
                        this.fields = await resp.json();
                    } catch(e) {
                        console.error('Failed to load fields', e);
                        this.fields = [];
                    }
                    this.loading = false;
                },

                selectField(id) {
                    this.selectedField = id;
                    this.selectedSlot = null;
                    this.selectedDate = '';
                    this.slots = [];
                    this.currentStep = 3;
                },

                async loadSlots() {
                    if (!this.selectedField || !this.selectedDate) return;
                    this.loading = true;
                    this.selectedSlot = null;
                    try {
                        const resp = await fetch('/customer/booking/field/' + this.selectedField + '/slots?date=' + this.selectedDate);
                        const data = await resp.json();
                        this.slots = data.slots || [];
                        if (data.field) {
                            // update price from field data
                            const f = this.fields.find(f => f.id === this.selectedField);
                            if (f) f.price_per_hour = data.field.price_per_hour;
                        }
                    } catch(e) {
                        console.error('Failed to load slots', e);
                        this.slots = [];
                    }
                    this.loading = false;
                },

                selectSlot(id) {
                    this.selectedSlot = id;
                },

                nextStep() {
                    if (this.currentStep < 3) this.currentStep++;
                },

                prevStep() {
                    if (this.currentStep > 1) this.currentStep--;
                },

                submitForm() {
                    document.getElementById('booking-form').submit();
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
                }
            }
        }
    </script>
</x-app-layout>
