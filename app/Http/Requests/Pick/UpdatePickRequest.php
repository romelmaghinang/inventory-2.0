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
            'pickNum' => ['required', 'numeric'],
            'locationName' => ['required', 'string', 'max:255', 'exists:location,name'],
            'partNum' => ['required', 'string', 'max:255', 'exists:part,num'],
            'partTrackingType' => ['required', 'string', 'exists:parttracking,name'],
            'trackingInfo' => ['nullable'],
            'priority' => ['required', 'integer', 'exists:priority,id'],
            'pickStatusId' => ['required', 'integer', 'exists:pickstatus,id'], // statusId
            'pickTypeId' => ['required', 'integer', 'exists:picktype,id'], // typeId

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
