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
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function register(RegisterRequest $request): JsonResponse
    {
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
