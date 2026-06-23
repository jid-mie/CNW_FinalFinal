<x-guest-layout>
    @if(request()->query('role') === 'owner')
        <div class="mb-6 text-center">
            <!-- Warning / Info Icon -->
            <div class="w-16 h-16 bg-[#fff2ee] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#ffd5cc]">
                <svg class="w-8 h-8 text-[#ff5722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-6h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold font-heading text-[#0f172a] uppercase tracking-wide">Đăng ký Đối Tác Chủ Sân</h2>
            <p class="text-[#45464d] mt-2 text-sm">
                Đăng ký tự động hiện đang tạm đóng để đảm bảo an toàn vận hành hệ thống.
            </p>
        </div>
        
        <div class="bg-[#f8fafc] border border-[#e2e8f0] p-5 rounded-lg mb-6 text-sm text-[#191c1e] space-y-4">
            <p class="font-semibold text-[#0f172a]">
                Để khởi tạo cụm sân và nhận tài khoản Quản lý Chủ Sân (Owner), vui lòng liên hệ Ban Quản Trị:
            </p>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#45464d] w-16">Hotline:</span>
                <a href="tel:0901234567" class="text-[#0f172a] font-bold hover:underline">0901 234 567</a>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#45464d] w-16">Email:</span>
                <a href="mailto:partner@playmanagement.vn" class="text-[#0f172a] font-bold hover:underline">partner@playmanagement.vn</a>
            </div>
            <div class="flex items-start gap-3">
                <span class="font-bold text-[#45464d] w-16">Lưu ý:</span>
                <span class="text-xs text-gray-500">Chúng tôi hỗ trợ cấu hình và bàn giao tài khoản hoạt động trong vòng 24 giờ làm việc.</span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="mailto:partner@playmanagement.vn?subject=Yeu%20cau%20khoi%20tao%20tai%20khoan%20chu%20san%20-%20PlayManagement" 
               class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-wider text-white bg-[#0f172a] hover:bg-[#1e293b] text-center transition-all">
                Gửi email yêu cầu đối tác
            </a>
            <a href="{{ route('register') }}" 
               class="w-full flex justify-center py-3.5 px-4 border border-[#e2e8f0] rounded-lg text-sm font-bold uppercase tracking-wider text-[#45464d] hover:bg-gray-50 text-center transition-all">
                Đăng ký tài khoản Khách hàng
            </a>
        </div>
    @else
        <div class="mb-6">
            <h2 class="text-3xl font-bold font-heading text-[#0f172a] uppercase tracking-wide">Tạo tài khoản mới</h2>
            <p class="text-[#45464d] mt-2 text-sm">Điền thông tin bên dưới để tham gia PlayManagement.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Họ và tên</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="Nguyễn Văn A">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-[#ba1a1a]" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Email</label>
                <div class="flex gap-2">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="email@example.com">
                    <button type="button" id="btn-send-otp" class="px-4 py-2.5 bg-[#0f172a] hover:bg-[#1e293b] text-[#4ade80] text-xs font-bold uppercase tracking-wider rounded-lg whitespace-nowrap active:scale-95 transition-all">
                        Gửi OTP
                    </button>
                </div>
                <div id="otp-feedback" class="text-xs mt-1.5 hidden font-semibold"></div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-[#ba1a1a]" />
            </div>

            <!-- OTP Code -->
            <div>
                <label for="otp" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Mã xác nhận (OTP)</label>
                <input id="otp" type="text" name="otp" required autocomplete="off" maxlength="6"
                    class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="Nhập 6 số OTP">
                <x-input-error :messages="$errors->get('otp')" class="mt-2 text-sm text-[#ba1a1a]" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Mật khẩu</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-[#ba1a1a]" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-[#191c1e] mb-1.5">Xác nhận mật khẩu</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="block w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#0f172a] focus:border-[#0f172a] transition-all text-sm bg-[#f8fafc] text-[#191c1e] placeholder-[#c6c6cd]" placeholder="••••••••">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-[#ba1a1a]" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 mt-6 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-wider text-[#4ade80] bg-[#0f172a] hover:bg-[#1e293b] hover:scale-[1.01] active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a] transition-all duration-150">
                Đăng ký
            </button>

            <p class="text-center text-xs text-[#45464d] mt-6">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="font-bold text-[#0f172a] hover:underline">Đăng nhập</a>
            </p>
        </form>

        <script>
            document.getElementById('btn-send-otp').addEventListener('click', function() {
                const emailInput = document.getElementById('email');
                const email = emailInput.value.trim();
                const feedback = document.getElementById('otp-feedback');
                const btn = this;

                if (!email) {
                    feedback.textContent = 'Vui lòng nhập địa chỉ email trước.';
                    feedback.className = 'text-xs mt-1.5 font-semibold text-[#ba1a1a]';
                    feedback.classList.remove('hidden');
                    emailInput.focus();
                    return;
                }

                // Simple email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    feedback.textContent = 'Địa chỉ email không đúng định dạng.';
                    feedback.className = 'text-xs mt-1.5 font-semibold text-[#ba1a1a]';
                    feedback.classList.remove('hidden');
                    emailInput.focus();
                    return;
                }

                feedback.textContent = 'Đang gửi mã OTP...';
                feedback.className = 'text-xs mt-1.5 font-semibold text-[#45464d]';
                feedback.classList.remove('hidden');
                btn.disabled = true;
                btn.textContent = 'Đang gửi...';

                fetch('{{ route('register.send-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(async response => {
                    const data = await response.json();
                    if (response.ok) {
                        feedback.textContent = 'Mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.';
                        feedback.className = 'text-xs mt-1.5 font-semibold text-[#15803d]';
                        
                        let countdown = 60;
                        btn.textContent = `Gửi lại (${countdown}s)`;
                        const interval = setInterval(() => {
                            countdown--;
                            if (countdown <= 0) {
                                clearInterval(interval);
                                btn.disabled = false;
                                btn.textContent = 'Gửi OTP';
                            } else {
                                btn.textContent = `Gửi lại (${countdown}s)`;
                            }
                        }, 1000);
                    } else {
                        btn.disabled = false;
                        btn.textContent = 'Gửi OTP';
                        const message = data.errors && data.errors.email ? data.errors.email[0] : (data.message || 'Gửi OTP thất bại.');
                        feedback.textContent = message;
                        feedback.className = 'text-xs mt-1.5 font-semibold text-[#ba1a1a]';
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.textContent = 'Gửi OTP';
                    feedback.textContent = 'Lỗi kết nối hệ thống. Vui lòng thử lại sau.';
                    feedback.className = 'text-xs mt-1.5 font-semibold text-[#ba1a1a]';
                    console.error(error);
                });
            });
        </script>
    @endif
</x-guest-layout>
