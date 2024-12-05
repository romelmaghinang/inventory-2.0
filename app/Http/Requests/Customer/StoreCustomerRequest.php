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
            'addressName' => ['required', 'string', 'unique:address,name'],  
            'addressContact' => ['required', 'string'],
            'addressType' => ['required', 'string', 'exists:addresstype,name'], 
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
            'mobile' => 'array',
            'mobile.*' => 'string|nullable',             
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
            'active' => ['nullable', 'boolean'], 
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
            'shippingTerms' => ['required', 'string', 'exists:shipterms,name'], 
            'alertNotes' => ['required', 'string'],
            'quickBooksClassName' => ['nullable', 'string', 'exists:qbclass,name'], 
            'toBeEmailed' => ['nullable', 'boolean'], 
            'toBePrinted' => ['nullable', 'boolean'], 
            'issuableStatus' => ['nullable', 'string'], 
            'cf'=> ['required', 'string'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'currencyName.exists' => 'The selected currency name is invalid.',
            'quickBooksClassName.exists' => 'The selected QuickBooks class name is invalid.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [];

        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'invalid')) {
                    $categorizedErrors['invalidFormat'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } elseif (str_contains($message, 'already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } else {
                    $categorizedErrors['otherErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }
        }

        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
