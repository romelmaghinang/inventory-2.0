<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarrierRequest extends FormRequest
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
            'carrierServiceName' => ['nullable', 'integer', 'min:0'],
            'carrierDescription' => ['nullable', 'string', 'max:255'],
            'readOnly' => ['boolean', 'nullable'],
            'carrierCode' => ['string', 'nullable', 'max:255'],
            'scac' => ['string', 'nullable', 'max:4'],
            'cost' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
        ];
    }
}