<?php

namespace App\Http\Requests\CountryAndState;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class StoreCountryAndStateRequest extends FormRequest
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
            'countryName' => ['required', 'string', 'unique:country,name'],
            'countryCode' => ['required', 'string'],

            'stateName' => ['required', 'string', 'unique:state,name'],
            'stateCode' =>  ['required', 'string'],
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
