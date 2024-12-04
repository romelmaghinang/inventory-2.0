<?php

namespace App\Http\Requests\Pick;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdatePickRequest extends FormRequest
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
            'pickNum' => ['nullable', 'numeric'],
            'locationName' => ['nullable', 'string', 'max:255', 'exists:location,name'],
            'partNum' => ['nullable', 'string', 'max:255', 'exists:part,num'],
            'partTrackingType' => ['nullable', 'string', 'exists:parttracking,name'],
            'trackingInfo' => ['nullable'],
            'priority' => ['nullable', 'integer', 'exists:priority,id'],
            'pickStatusId' => ['nullable', 'integer', 'exists:pickstatus,id'], // statusId
            'pickTypeId' => ['nullable', 'integer', 'exists:picktype,id'], // typeId
            'uniqueField' => ['nullable', 'string', 'unique:pick,unique_field,' . $this->route('pick')], // Example field with unique validation
        ];
    }
    

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'invalidData' => [],
            'duplicateFields' => [],
        ];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['invalidData'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                }
            }
        }

        $response = response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors), 
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        throw new HttpResponseException($response);
    }
}
