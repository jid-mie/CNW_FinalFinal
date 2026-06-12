<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', app()->runningUnitTests() ? 'email' : 'email:rfc,dns', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp' => ['required', 'string', 'size:6'],
        ];
    }
}
