<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'field_id' => ['required', 'integer', 'exists:fields,id'],
            'time_slot_id' => ['required', 'integer', 'exists:time_slots,id'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'field_id.required' => 'Field is required',
            'field_id.exists' => 'Selected field does not exist',
            'time_slot_id.required' => 'Time slot is required',
            'time_slot_id.exists' => 'Selected time slot does not exist',
            'booking_date.required' => 'Booking date is required',
            'booking_date.date_format' => 'Booking date must be in format Y-m-d',
            'booking_date.after_or_equal' => 'Booking date must be today or later',
        ];
    }
}
