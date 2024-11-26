<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateLocationRequest extends FormRequest
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
            'location' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string', 'max:90'],
            'type' => ['required', 'string', 'exists:locationtype,name'],
            'locationGroup' => ['required', 'string', 'max:30'],
            'locationNum' => ['nullable', 'numeric'],
            'customerName' => ['nullable', 'string', 'max:30'],
            'active' => ['required', 'boolean'],
            'available' => ['required', 'boolean'],
            'pickable' => ['required', 'boolean'],
            'receivable' => ['required', 'boolean'],
            'sortOrder' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
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
