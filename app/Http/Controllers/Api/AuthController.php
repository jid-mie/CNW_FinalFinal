<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\LogoutRequest;
use App\Http\Requests\Api\Auth\RefreshTokenRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendOtpMail;
use App\Models\FailedLoginAttempt;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            FailedLoginAttempt::create([
                'email' => $request->input('email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempted_at' => now(),
            ]);

            return $this->errorResponse('Invalid email or password', 401);
        }

        $accessToken = $user->createToken('access-token', ['access'], now()->addMinutes(config('sanctum.access_expiration', 15)));
        $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addMinutes(config('sanctum.refresh_expiration', 43200)));

        return $this->successResponse([
            'user' => new UserResource($user->load('role')),
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.access_expiration', 15) * 60,
        ], 'Login successful');
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $token = PersonalAccessToken::findToken($request->refresh_token);

        if (! $token || ! $token->can('refresh')) {
            return $this->errorResponse('Invalid or expired refresh token', 401);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            return $this->errorResponse('Refresh token has expired', 401);
        }

        if (isset($token->is_active) && ! $token->is_active) {
            return $this->errorResponse('Refresh token has been temporarily deactivated', 401);
        }

        $user = $token->tokenable;

        $token->delete();

        $accessToken = $user->createToken('access-token', ['access'], now()->addMinutes(config('sanctum.access_expiration', 15)));
        $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addMinutes(config('sanctum.refresh_expiration', 43200)));

        return $this->successResponse([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.access_expiration', 15) * 60,
        ], 'Token refreshed successfully');
    }

    public function logout(LogoutRequest $request): JsonResponse
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        // Delete the specific refresh token
        $refreshToken = PersonalAccessToken::findToken($request->refresh_token);
        if ($refreshToken && $refreshToken->can('refresh') && $refreshToken->tokenable_id === $request->user()->id) {
            $refreshToken->delete();
        }

        return $this->successResponse(null, 'Logged out successfully');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->successResponse(
            new UserResource($request->user()->load('role')),
            'User profile retrieved successfully'
        );
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->safe()->except(['current_password', 'new_password', 'new_password_confirmation']);

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);

        return $this->successResponse(
            new UserResource($user->fresh()->load('role')),
            'Cập nhật thông tin thành công'
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $ownerRole = Role::where('name', 'owner')->first();
        $isAttemptingOwner = false;

        if ($request->has('role') && strtolower($request->input('role')) === 'owner') {
            $isAttemptingOwner = true;
        }
        if ($request->has('role_name') && strtolower($request->input('role_name')) === 'owner') {
            $isAttemptingOwner = true;
        }
        if ($ownerRole && $request->has('role_id') && (int) $request->input('role_id') === (int) $ownerRole->id) {
            $isAttemptingOwner = true;
        }

        if ($isAttemptingOwner) {
            return $this->errorResponse('Đăng ký tài khoản chủ sân không được phép.', 403);
        }

        $cachedOtp = Cache::get('otp_'.$request->email);
        if (! $cachedOtp || $cachedOtp !== $request->otp) {
            throw ValidationException::withMessages([
                'otp' => ['Mã OTP không chính xác hoặc đã hết hạn.'],
            ]);
        }

        Cache::forget('otp_'.$request->email);

        $customerRole = Role::firstOrCreate(
            ['name' => RoleEnum::CUSTOMER->value],
            ['display_name' => 'Customer']
        );

        $user = User::create([
            'role_id' => $customerRole->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $accessToken = $user->createToken('access-token', ['access'], now()->addMinutes(config('sanctum.access_expiration', 15)));
        $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addMinutes(config('sanctum.refresh_expiration', 43200)));

        return $this->successResponse([
            'user' => new UserResource($user->load('role')),
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.access_expiration', 15) * 60,
        ], 'Registration successful. Please check your email for verification.', 201);
    }

    /**
     * Send OTP for API registration.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', app()->runningUnitTests() ? 'email' : 'email:rfc,dns', 'max:255', 'unique:'.User::class],
        ]);

        $otpCode = app()->runningUnitTests() ? '123456' : (string) rand(100000, 999999);
        Cache::put('otp_'.$request->email, $otpCode, now()->addMinutes(5));

        Mail::to($request->email)->send(new SendOtpMail($otpCode));

        return $this->successResponse(null, 'Mã OTP đã được gửi thành công đến email của bạn.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        Password::sendResetLink(
            $request->only('email')
        );

        // Always return success to prevent User Enumeration
        return $this->successResponse(null, 'If the email exists in our system, we have sent a password reset link.');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->successResponse(null, __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
