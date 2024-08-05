<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class StoreCustomerRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:41', 'unique:customer,name'],
            'addressName' => ['required','string'],  
            'addressContact' => ['required','string'],
            'addressType' => ['required','string',  'exists:addresstype,name'], 
            'isDefault' => ['required', 'boolean'], 
            'address' => ['nullable', 'string', 'max:90'], 
            'city' => ['nullable', 'string', 'max:30'], 
            'state' => ['nullable', 'string', 'exists:state,name'], 
            'zip' => ['nullable', 'string', 'max:10'], 
            'country' => ['nullable', 'string', 'exists:country,name'],
            'resident' => ['required', 'boolean'],
            'main' => ['required', 'string'],
            'home' => ['required', 'string'],
            'work' =>  ['required', 'string'],
            'mobile'=> ['required', 'string'],
            'fax'=> ['required', 'string'],
            'email'=> ['required', 'string','email'],
            'pager' => ['required', 'string'],
            'web' => ['required', 'string'],
            'other' => ['required', 'string'],
            'currencyName' => ['required', 'string', 'exists:currency,name'],
            'currencyRate' => ['required', 'numeric'],
            'group' => ['required', 'string'],
            'creditLimit' => ['nullable', 'numeric'], 
            'status' => ['nullable', 'string', 'exists:customerstatus,name'], 
            'active' => ['nullable', 'boolean',], 
            'taxRate' => ['nullable', 'string', 'exists:taxrate,name'], 
            'salesman' => ['nullable', 'integer'], 
            'defaultPriority' => ['required', 'string', 'exists:priority,name'], 
            'number' => ['nullable', 'string', 'max:30', 'unique:customer,name'], 
            'paymentTerms' => ['nullable', 'string', 'exists:paymentterms,name'], 
            'taxExempt' => ['nullable', 'boolean'], 
            'taxExemptNumber' => ['nullable', 'string', 'max:30'], 
            'url' => ['nullable', 'url', 'max:30'], 
            'carrierName' => ['nullable', 'string', 'exists:carrier,name'], 
            'carrierService' => ['nullable', 'string', 'exists:carrierservice,name'], 
            'shippingTerms' => ['nullable', 'string'], 
            'alertNotes' => ['required', 'string'],
            'quickBooksClassName' => ['nullable', 'string', 'exists:qbclass,name'], 
            'toBeEmailed' => ['nullable', 'boolean'], 
            'toBePrinted' => ['nullable', 'boolean'], 
            'issuableStatus' => ['nullable', 'string'], 
            'cf'=> ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}