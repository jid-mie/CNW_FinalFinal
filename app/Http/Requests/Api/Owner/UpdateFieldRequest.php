<?php

namespace App\Http\Requests\Api\Owner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('owner') ?? false;
    }

    public function rules(): array
    {
        $field = $this->route('field');
        $fieldId = $field instanceof Model ? $field->id : $field;

        return [
            'sport_id' => ['sometimes', 'exists:sports,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50', 'unique:fields,code,'.$fieldId],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['sometimes', 'string', 'max:500'],
            'price_per_hour' => ['sometimes', 'numeric', 'min:0', 'max:999999999.99'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i', 'after:open_time'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['nullable', 'in:active,maintenance,inactive'],
        ];
    }
}
