<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $ownerRole = Role::where('name', 'owner')->first();
        $isAttemptingOwner = false;

        if ($request->has('role') && strtolower($request->input('role')) === 'owner') {
            $isAttemptingOwner = true;
        }
        if ($request->has('role_name') && strtolower($request->input('role_name')) === 'owner') {
            $isAttemptingOwner = true;
        }
        if ($ownerRole && $request->has('role_id') && (int)$request->input('role_id') === (int)$ownerRole->id) {
            $isAttemptingOwner = true;
        }

        if ($isAttemptingOwner) {
            abort(403, 'Đăng ký tài khoản chủ sân không được phép.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', app()->runningUnitTests() ? 'email' : 'email:rfc,dns', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);
        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            throw ValidationException::withMessages([
                'otp' => ['Mã OTP không chính xác hoặc đã hết hạn.'],
            ]);
        }

        Cache::forget('otp_' . $request->email);

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer']
        );

        $user = User::create([
            'role_id' => $customerRole->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        return redirect(route('login', absolute: false));
    }

    /**
     * Send OTP for web registration.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', app()->runningUnitTests() ? 'email' : 'email:rfc,dns', 'max:255', 'unique:'.User::class],
        ]);

        $otpCode = app()->runningUnitTests() ? '123456' : (string)rand(100000, 999999);
        Cache::put('otp_' . $request->email, $otpCode, now()->addMinutes(5));

        Mail::to($request->email)->send(new SendOtpMail($otpCode));

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP đã được gửi thành công đến email của bạn.'
        ]);
    }
}
