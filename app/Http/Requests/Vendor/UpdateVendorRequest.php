<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateVendorRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:41'],
            'addressName' => ['required', 'string', 'max:90', 'unique:address,name'],
            'addressContact' => ['string', 'max:41'],
            'addressType' => ['required', 'integer', 'in:10,20,30,40,50', 'exists:addresstype,id'],
            'isDefault' => ['nullable', 'boolean'],
            'address' => ['nullable', 'string', 'max:90'],
            'city' => ['nullable', 'string', 'max:30'],
            'state' => ['nullable', 'string', 'max:30', 'exists:state,name'],
            'zip' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:64', 'exists:country,name'],
            'main' => ['nullable', 'string', 'max:64'],
            'home' => ['nullable', 'string', 'max:64'],
            'work' => ['nullable', 'string', 'max:64'],
            'mobile' => ['nullable', 'string', 'max:64'],
            'fax' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'email', 'max:64'],
            'pager' => ['nullable', 'string', 'max:64'],
            'web' => ['nullable', 'url', 'max:64'],
            'other' => ['nullable', 'string', 'max:64'],
            'currencyName' => ['nullable', 'string', 'max:255', 'exists:currency,name'],
            'currencyRate' => ['nullable', 'numeric'],
            'defaultTerms' => ['nullable', 'string', 'max:30'],
            'defaultCarrier' => ['nullable', 'string', 'exists:carrier,name'],
            'defaultShippingTerms' => ['nullable', 'string', 'exists:shipterms,name'],
            'status' => ['nullable', 'string', 'exists:vendorstatus,name'],
            'accountNumber' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'minOrderAmount' => ['nullable', 'numeric'],
            'alertNotes' => ['nullable', 'string', 'max:90'],
            'url' => ['nullable', 'url', 'max:256'],
            'cf' => ['nullable', 'string', 'max:30'],
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
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid') || str_contains($message, 'is invalid')) {
                    $categorizedErrors['invalidFormat'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = $field;
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
