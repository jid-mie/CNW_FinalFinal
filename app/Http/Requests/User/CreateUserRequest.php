<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $passwordRules = $this->isMethod('post')
            ? ['required', 'string', 'min:8']
            : ['nullable', 'string', 'min:8'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => $passwordRules,
            'role_id' => ['nullable', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function passedValidation(): void
    {
        if (($this->isMethod('put') || $this->isMethod('patch')) && blank($this->input('password'))) {
            $this->request->remove('password');
        }
    }
}
