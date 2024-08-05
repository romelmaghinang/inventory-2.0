<?php

namespace App\Http\Requests\PaymentTerms;

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
            'termsName' => ['required', 'string', 'unique:paymentterms,name'], // name
            'termsType' =>  ['required', 'string' , 'exists:paymenttermstype,name'], // typeId
            'netDays' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'discountDays' => ['required', 'numeric'],
            'dueDate' => ['required', 'date'],
            'nextMonth'=> ['required', 'date'],
            'discountDate' => ['required', 'date'],
            'default' => ['required', 'boolean'], // defaultTerms
            'active' => ['required','boolean'], // activeFlag
        ];
    }
}
