<?php

namespace App\Http\Requests\Admin\Sport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Lấy ID từ đường dẫn /sports/{id}/update để loại trừ trùng tên với chính nó
        $sportId = $this->route('id');

        return [
            'name' => ['required', 'string', 'unique:sports,name,'.$sportId, 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
