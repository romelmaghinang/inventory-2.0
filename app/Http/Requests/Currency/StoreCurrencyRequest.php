<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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
            'currencyName' => ['nullable', 'integer', 'min:0'], // Currency ID
            'currencyRate' => ['nullable', 'numeric'],
            'currencyCode' => ['string', 'nullable', 'max:255'],
            'excludeFromUpdate' => ['boolean'],
            'homeCurrency' => ['boolean'],
            'currencySymbol' => ['integer', 'nullable'],
        ];
    }
}
