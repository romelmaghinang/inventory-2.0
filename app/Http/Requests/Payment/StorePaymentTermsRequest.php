<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTermsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paymentTermsName' => ['nullable', 'integer', 'min:0'],
            'defaultTerm' => ['boolean'],
            'discount' => ['nullable', 'numeric', 'between:0,999999.99'],
            'discountDays' => ['nullable', 'integer'],
            'netDays' => ['nullable', 'integer'],
            'nextMonth' => ['nullable', 'integer'],
        ];
    }
}
