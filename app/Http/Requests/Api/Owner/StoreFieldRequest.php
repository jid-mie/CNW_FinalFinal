<?php

namespace App\Http\Requests\Api\Owner;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('owner') ?? false;
    }

    public function rules(): array
    {
        return [
            'sport_id' => ['required', 'exists:sports,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:fields,code'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['required', 'string', 'max:500'],
            'price_per_hour' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i', 'after:open_time'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['nullable', 'in:active,maintenance,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'sport_id.required' => 'Vui lòng chọn loại sân',
            'sport_id.exists' => 'Loại sân không hợp lệ',
            'name.required' => 'Vui lòng nhập tên sân',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'price_per_hour.required' => 'Vui lòng nhập giá thuê',
            'price_per_hour.min' => 'Giá thuê không được âm',
            'close_time.after' => 'Giờ đóng cửa phải sau giờ mở cửa',
        ];
    }
}
