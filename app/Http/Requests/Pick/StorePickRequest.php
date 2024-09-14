<?php

namespace App\Http\Requests\Pick;

use App\Rules\PartTrackingTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StorePickRequest extends FormRequest
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
            '*.pickNum' => ['required', 'numeric', 'exists:so,num'],
            '*.locationName' => ['required', 'string', 'max:255', 'exists:location,name'],
            '*.partNum' => ['required', 'string', 'max:255', 'exists:part,num'],
            '*.partTrackingType' => ['required', 'string', 'exists:parttracking,name'],
            '*.trackingInfo' => ['nullable'],
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
