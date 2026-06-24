<?php

namespace App\Http\Requests\Admin\Sport;

use Illuminate\Foundation\Http\FormRequest;

class StoreSportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Bật true để cho phép quyền thực thi
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:sports,name', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
