<?php

namespace App\Http\Requests\TaxRate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateTaxRateRequest extends FormRequest
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
        $taxRateId = $this->route('taxrate'); 

        return [
            // TAX RATE
            'taxName' => [
                'nullable',
                'string',
                'max:31',
                "unique:taxrate,name,{$taxRateId}" 
            ],
            'taxCode' => [
                'nullable',
                'string',
                'max:5',
                "unique:taxrate,code,{$taxRateId}" 
            ],
            'taxType' => ['nullable', 'string', 'exists:taxratetype,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'rate' => ['nullable', 'numeric'],
            'amount' => ['nullable', 'numeric'],
            'taxAgencyName' => ['nullable', 'string'],
            'defaultFlag' => ['nullable', 'boolean'],
            'activeFlag' => ['nullable', 'boolean'],
        ];
        
    }

    /**
     * Handle failed validation and categorize the errors.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'duplicateFields' => [],
            'relatedFieldErrors' => [],
        ];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = $field;
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
