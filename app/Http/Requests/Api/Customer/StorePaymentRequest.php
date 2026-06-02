<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['nullable', 'in:cash,bank_transfer,momo,vnpay'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
