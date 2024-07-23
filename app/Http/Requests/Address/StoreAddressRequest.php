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
