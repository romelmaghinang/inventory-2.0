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
