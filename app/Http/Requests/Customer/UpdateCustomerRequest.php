<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateCustomerRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:41', 'unique:customer,name,' . $this->route('customer')],
            'addressName' => ['nullable', 'string'],
            'addressContact' => ['nullable', 'string'],
            'addressType' => ['nullable', 'string', 'exists:addresstype,name'],
            'isDefault' => ['nullable', 'boolean'],
            'address' => ['nullable', 'string', 'max:90'],
            'city' => ['nullable', 'string', 'max:30'],
            'state' => ['nullable', 'string', 'exists:state,name'],
            'zip' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'exists:country,name'],
            'resident' => ['nullable', 'boolean'],
            'main' => ['nullable', 'string'],
            'home' => ['nullable', 'string'],
            'work' => ['nullable', 'string'],
            'mobile' => ['nullable', 'string'],
            'fax' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'pager' => ['nullable', 'string'],
            'web' => ['nullable', 'string'],
            'other' => ['nullable', 'string'],
            'currencyName' => ['nullable', 'string', 'exists:currency,name'],
            'currencyRate' => ['nullable', 'numeric'],
            'group' => ['nullable', 'string'],
            'creditLimit' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'exists:customerstatus,name'],
            'active' => ['nullable', 'boolean'],
            'taxRate' => ['nullable', 'string', 'exists:taxrate,name'],
            'salesman' => ['nullable', 'integer'],
            'defaultPriority' => ['nullable', 'string', 'exists:priority,name'],
            'number' => ['nullable', 'string', 'max:30', 'unique:customer,name,' . $this->route('customer')],
            'paymentTerms' => ['nullable', 'string', 'exists:paymentterms,name'],
            'taxExempt' => ['nullable', 'boolean'],
            'taxExemptNumber' => ['nullable', 'string', 'max:30'],
            'url' => ['nullable', 'url', 'max:30'],
            'carrierName' => ['nullable', 'string', 'exists:carrier,name'],
            'carrierService' => ['nullable', 'string', 'exists:carrierservice,name'],
            'shippingTerms' => ['nullable', 'string', 'exists:shipterms,name'],
            'alertNotes' => ['nullable', 'string'],
            'quickBooksClassName' => ['nullable', 'string', 'exists:qbclass,name'],
            'toBeEmailed' => ['nullable', 'boolean'],
            'toBePrinted' => ['nullable', 'boolean'],
            'issuableStatus' => ['nullable', 'string'],
            'cf' => ['nullable', 'string'],
        ];
        
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
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
