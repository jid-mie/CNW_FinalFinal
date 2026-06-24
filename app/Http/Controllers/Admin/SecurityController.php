<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedLoginAttempt;
use Laravel\Sanctum\PersonalAccessToken;

class SecurityController extends Controller
{
    /**
     * Display the admin security settings dashboard.
     */
    public function index()
    {
        // 1. Get system security metrics
        $systemChecks = [
            [
                'name' => 'Chế độ Debug (APP_DEBUG)',
                'status' => ! config('app.debug') ? 'secure' : 'warning',
                'value' => config('app.debug') ? 'BẬT (Không an toàn cho Product)' : 'TẮT (An toàn)',
                'description' => 'Chế độ Debug chỉ nên được bật trong môi trường thử nghiệm (Local) và tắt khi triển khai chính thức.',
            ],
            [
                'name' => 'Môi trường vận hành (APP_ENV)',
                'status' => config('app.env') === 'production' ? 'secure' : 'info',
                'value' => strtoupper(config('app.env')),
                'description' => 'Môi trường hệ thống hiện tại đang chạy.',
            ],
            [
                'name' => 'Kết nối Bảo mật (HTTPS/SSL)',
                'status' => request()->secure() ? 'secure' : 'warning',
                'value' => request()->secure() ? 'Đang sử dụng HTTPS' : 'Chưa sử dụng HTTPS (HTTP)',
                'description' => 'HTTPS mã hóa dữ liệu truyền tải giữa người dùng và máy chủ.',
            ],
            [
                'name' => 'Hệ quản trị cơ sở dữ liệu',
                'status' => 'info',
                'value' => strtoupper(config('database.default')),
                'description' => 'Hệ thống cơ sở dữ liệu hiện tại dùng để lưu trữ.',
            ],
            [
                'name' => 'Phiên bản PHP',
                'status' => 'info',
                'value' => 'PHP '.PHP_VERSION,
                'description' => 'Phiên bản ngôn ngữ chạy mã nguồn phía Server.',
            ],
            [
                'name' => 'Phiên bản Laravel',
                'status' => 'info',
                'value' => 'Laravel '.app()->version(),
                'description' => 'Phiên bản framework sử dụng cho hệ thống.',
            ],
        ];

        // 2. Fetch active API personal access tokens
        $tokens = PersonalAccessToken::with('tokenable')
            ->orderBy('id', 'desc')
            ->get();

        // 3. Fetch recent failed login attempts
        $failedAttempts = FailedLoginAttempt::orderBy('attempted_at', 'desc')
            ->take(50)
            ->get();

        return view('admin.security.index', compact('systemChecks', 'tokens', 'failedAttempts'));
    }

    /**
     * Toggle the active status of an API Token.
     */
    public function toggleToken($id)
    {
        $token = PersonalAccessToken::findOrFail($id);
        $token->is_active = ! $token->is_active;
        $token->save();

        $statusText = $token->is_active ? 'Kích hoạt lại' : 'Vô hiệu hóa';

        return redirect()->route('admin.security.index')->with('success', "{$statusText} Token API thành công!");
    }

    /**
     * Clear all failed login logs.
     */
    public function clearLogs()
    {
        FailedLoginAttempt::truncate();

        return redirect()->route('admin.security.index')->with('success', 'Đã xóa toàn bộ nhật ký đăng nhập thất bại!');
    }
}
