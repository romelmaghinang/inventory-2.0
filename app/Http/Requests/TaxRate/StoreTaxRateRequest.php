<?php

namespace App\Http\Requests\TaxRate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

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
            // TAX RATE
            'taxName' => ['required', 'string', 'max:31'], // Tax Rate ID 
            'taxCode' => ['required', 'string', 'max:5'],
            'taxType' => ['required', 'string', 'exists:taxratetype,name'],
            'description' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'taxAgencyName' => ['required', 'string'],
            'defaultFlag' => ['required', 'boolean'],
            'activeFlag' => ['required', 'boolean'],
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
