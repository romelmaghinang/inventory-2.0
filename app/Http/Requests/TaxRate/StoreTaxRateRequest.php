<?php

namespace App\Http\Requests\TaxRate;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRateRequest extends FormRequest
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
            'taxRate' => ['nullable', 'numeric'],
            'taxRateName' => ['string', 'nullable', 'max:31'], // Tax Rate ID
            'taxRateCode' => ['nullable', 'string', 'max:5'],
            'taxRateDescription' => ['nullable', 'string', 'max:255'],
            'mcTotalTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'estimatedTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'totalIncludesTax' => ['boolean'],
            'totalTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
        ];
    }
}
