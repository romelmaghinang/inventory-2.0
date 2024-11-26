<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreAddressRequest extends FormRequest
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
            'accountId' => ['required', 'integer', 'min:0'],
            'name' => ['required', 'string', 'max:41'],
            'city' => ['nullable', 'string', 'max:30'],
            'countryId' => ['nullable', 'integer', 'min:0'],
            'defaultFlag' => ['required', 'boolean'],
            'locationGroupId' => ['nullable', 'integer', 'min:0'],
            'addressName' => ['nullable', 'string', 'max:90', 'unique:table_name,addressName'],
            'pipelineContactNum' => ['nullable', 'integer'],
            'stateId' => ['nullable', 'integer', 'min:0'],
            'address' => ['required', 'string', 'max:90'],
            'typeID' => ['nullable', 'integer', 'min:0'],
            'zip' => ['nullable', 'string', 'max:10'],
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
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = $field;
                }
            }
        }

        // Return the categorized errors in a structured response
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
