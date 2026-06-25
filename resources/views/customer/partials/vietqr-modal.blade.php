<!-- 💳 SEEPAY VIETQR PAYMENT MODAL -->
<div id="vietqr-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closeVietQRModal()"></div>
    
    <!-- Modal Box -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden border border-slate-100 flex flex-col md:flex-row transition-all transform scale-95 opacity-0 duration-300 z-10" id="modal-box">
        
        <!-- Left: VietQR Image Column -->
        <div class="w-full md:w-1/2 p-6 bg-slate-50 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100">
            <div class="text-center mb-4">
                <span class="bg-[#3cd882]/10 text-emerald-800 text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-xl border border-[#3cd882]/20">
                    VietQR Tự Động
                </span>
                <h4 class="font-extrabold text-sm text-slate-900 mt-2.5">Quét mã để thanh toán</h4>
            </div>
            
            <!-- QR Code Box with scan animation -->
            <div class="relative bg-white border border-slate-100 p-4 rounded-2xl shadow-xs flex items-center justify-center w-52 h-52 overflow-hidden">
                <img id="qr-code-img" src="" alt="VietQR Code" class="w-full h-full object-contain">
                <!-- Laser Scan Line -->
                <div class="absolute left-0 right-0 h-0.5 bg-[#3cd882] shadow-[0_0_8px_rgba(60,216,130,0.8)] scan-line" style="top: 0;"></div>
            </div>
            
            <p class="text-[10px] text-slate-500 text-center mt-4 leading-relaxed font-semibold">
                Mở ứng dụng ngân hàng hoặc ví điện tử bất kỳ quét mã để hoàn tất chuyển khoản tự động.
            </p>
        </div>
        
        <!-- Right: Details Column -->
        <div class="w-full md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="flex items-start justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-black text-base text-slate-900 uppercase tracking-tight">Thông tin chuyển khoản</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5" id="modal-field-name">Sân bóng</p>
                    </div>
                    <button onclick="closeVietQRModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- Copyable bank details -->
                <div class="space-y-2.5">
                    <!-- Bank Brand -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Ngân hàng</span>
                            <span class="text-xs font-extrabold text-slate-900 uppercase" id="bank-brand-text">Vietinbank</span>
                        </div>
                        <button onclick="copyToClipboard('bank-brand-text', this)" class="text-[9px] font-black text-slate-900 hover:text-[#3cd882] bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs active:scale-95 transition-all">Sao chép</button>
                    </div>
                    
                    <!-- Bank Account -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Số tài khoản</span>
                            <span class="text-xs font-extrabold text-slate-900" id="bank-account-text">113000045678</span>
                        </div>
                        <button onclick="copyToClipboard('bank-account-text', this)" class="text-[9px] font-black text-slate-900 hover:text-[#3cd882] bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs active:scale-95 transition-all">Sao chép</button>
                    </div>

                    <!-- Account Name -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Tên tài khoản</span>
                            <span class="text-xs font-extrabold text-slate-900 uppercase" id="account-name-text">NGUYEN VAN A</span>
                        </div>
                        <button onclick="copyToClipboard('account-name-text', this)" class="text-[9px] font-black text-slate-900 hover:text-[#3cd882] bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs active:scale-95 transition-all">Sao chép</button>
                    </div>

                    <!-- Amount -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Số tiền</span>
                            <span class="text-xs font-black text-slate-900" id="amount-text">0đ</span>
                        </div>
                        <button onclick="copyToClipboard('amount-text-raw', this)" class="text-[9px] font-black text-slate-900 hover:text-[#3cd882] bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs active:scale-95 transition-all">Sao chép</button>
                        <span id="amount-text-raw" class="hidden">0</span>
                    </div>

                    <!-- Transfer Code / Description -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 border border-[#3cd882]/20">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Nội dung chuyển khoản (Bắt buộc)</span>
                            <span class="text-sm font-black text-emerald-800 uppercase tracking-widest" id="transfer-code-text">PLAYXXXX</span>
                        </div>
                        <button onclick="copyToClipboard('transfer-code-text', this)" class="text-[9px] font-black text-emerald-800 hover:text-white hover:bg-emerald-600 bg-white border border-emerald-500/20 px-3 py-1.5 rounded-lg shadow-xs active:scale-95 transition-all">Sao chép</button>
                    </div>
                </div>
            </div>
            
            <!-- Status/Footer -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col items-center gap-3">
                <!-- Status Message with Spinner -->
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500" id="payment-status-container">
                    <svg class="animate-spin h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="payment-status-text">Đang quét giao dịch chuyển khoản...</span>
                </div>
                
                <button onclick="closeVietQRModal()" class="w-full bg-slate-900 text-[#3cd882] py-3 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-slate-800 transition-colors shadow-sm cursor-pointer">
                    Đóng cửa sổ
                </button>
            </div>
        </div>
        
        <!-- Success Overlay inside modal -->
        <div id="payment-success-overlay" class="absolute inset-0 bg-white z-20 flex flex-col items-center justify-center hidden transition-opacity duration-300">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Thanh toán thành công!</h3>
            <p class="text-xs text-slate-500 mt-2 text-center max-w-sm px-6 font-semibold">
                Hệ thống đã ghi nhận thanh toán của bạn qua ngân hàng. Lịch đặt sân đã chuyển sang trạng thái **Đã xác nhận**.
            </p>
            <div class="mt-6 flex items-center gap-2 text-xs text-slate-400 font-extrabold animate-pulse">
                <span>Đang làm mới trang...</span>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes scan {
        0%, 100% { top: 0%; }
        50% { top: 100%; }
    }
    .scan-line {
        animation: scan 3s linear infinite;
    }
</style>

<script>
    const SEEPAY_BANK_ID = "{{ config('services.seepay.bank_id', 'vietinbank') }}";
    const SEEPAY_BANK_ACCOUNT = "{{ config('services.seepay.bank_account', '113000045678') }}";
    const SEEPAY_ACCOUNT_NAME = "{{ config('services.seepay.account_name', 'NGUYEN VAN A') }}";

    let pollingInterval = null;
    let activeBookingId = null;

    function openVietQRModal(bookingId, fieldName, amount) {
        activeBookingId = bookingId;
        
        // Set text values
        document.getElementById('modal-field-name').innerText = fieldName;
        document.getElementById('bank-brand-text').innerText = SEEPAY_BANK_ID;
        document.getElementById('bank-account-text').innerText = SEEPAY_BANK_ACCOUNT;
        document.getElementById('account-name-text').innerText = SEEPAY_ACCOUNT_NAME;
        document.getElementById('amount-text').innerText = new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        document.getElementById('amount-text-raw').innerText = amount;
        document.getElementById('transfer-code-text').innerText = 'PLAY' + bookingId;

        // Generate VietQR Image URL
        const qrUrl = `https://img.vietqr.io/image/${SEEPAY_BANK_ID}-${SEEPAY_BANK_ACCOUNT}-compact2.png?amount=${amount}&addInfo=PLAY${bookingId}&accountName=${encodeURIComponent(SEEPAY_ACCOUNT_NAME)}`;
        document.getElementById('qr-code-img').src = qrUrl;

        // Show Modal with animation
        const modal = document.getElementById('vietqr-modal');
        const box = document.getElementById('modal-box');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Hide success overlay if it was open previously
        document.getElementById('payment-success-overlay').classList.add('hidden');

        // Start Polling Status
        startPolling(bookingId);
    }

    function openVietQRModalFromButton(button) {
        const bookingId = button.dataset.bookingId;
        const fieldName = button.dataset.fieldName;
        const amount = Number(button.dataset.amount || 0);

        openVietQRModal(bookingId, fieldName, amount);
    }

    function closeVietQRModal() {
        stopPolling();
        
        const modal = document.getElementById('vietqr-modal');
        const box = document.getElementById('modal-box');
        
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function startPolling(bookingId) {
        stopPolling();

        pollingInterval = setInterval(() => {
            fetch(`/customer/bookings/${bookingId}/status`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.is_paid || data.status === 'confirmed') {
                        stopPolling();
                        showSuccessOverlay();
                    }
                })
                .catch(error => {
                    console.error('Error polling booking status:', error);
                });
        }, 1000);
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function showSuccessOverlay() {
        const overlay = document.getElementById('payment-success-overlay');
        overlay.classList.remove('hidden');
        
        setTimeout(() => {
            window.location.reload();
        }, 2500);
    }

    function copyToClipboard(elementId, button) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = button.innerText;
            button.innerText = 'Đã chép!';
            button.classList.add('bg-emerald-100', 'text-emerald-800');
            
            setTimeout(() => {
                button.innerText = originalText;
                button.classList.remove('bg-emerald-100', 'text-emerald-800');
            }, 1500);
        }).catch(err => {
            console.error('Could not copy text: ', err);
        });
    }
</script>
