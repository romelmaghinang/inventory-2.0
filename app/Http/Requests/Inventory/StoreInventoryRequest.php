<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Symfony\Component\HttpFoundation\Response;

class StoreInventoryRequest extends FormRequest
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
            'PartNumber' => ['required', 'string', 'max:70'],
            'PartDescription' => ['nullable', 'string', 'max:252'],
            'Location' => ['required', 'string', 'exists:location,name'],
            'Qty' => ['required', 'numeric'],
            'UOM' => ['required', 'string', 'exists:uom,name'],
            'Cost' => ['required', 'numeric', 'max:9999999999'],
            'QbClass' => ['nullable', 'string', 'exists:qbclass,name'],
            'Date' => ['nullable', 'date'],
            'Note' => ['nullable', 'string'],
            'TrackingType' => ['nullable', 'string', 'exists:parttracking,name'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'duplicateFields' => [],
            'relatedFieldErrors' => [],
        ];

        foreach ($validator->errors()->toArray() as $field => $messages) {
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
