<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class StoreCustomerRequest extends FormRequest
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
            'accountTypeId' => ['nullable', 'integer'], 
            'activeFlag' => ['nullable', 'boolean'], 
            'creditLimit' => ['nullable', 'numeric'],
            'currencyId' => ['nullable', 'integer'], 
            'currencyRate' => ['nullable', 'numeric'], 
            'defaultCarrierId' => ['nullable', 'integer'], 
            'defaultPaymentTermsId' => ['nullable', 'integer'], 
            'defaultSalesmanId' => ['nullable', 'integer'], 
            'defaultShipTermsId' => ['nullable', 'integer'],
            'customerName' => ['nullable', 'string', 'max:41'],
            'note' => ['nullable', 'string', 'max:90'], 
            'number' => ['nullable', 'string', 'max:30'], 
            'qbClassId' => ['nullable', 'integer'], 
            'statusId' => ['nullable', 'integer'], 
            'taxExempt' => ['nullable', 'boolean'], 
            'taxExemptNumber' => ['nullable', 'string', 'max:30'],
            'taxRateId' => ['nullable', 'integer'], 
            'toBeEmailed' => ['nullable', 'boolean'], 
            'toBePrinted' => ['nullable', 'boolean'],
            'url' => ['nullable', 'url', 'max:30'],
            'issuableStatusId' => ['nullable', 'integer'],
            'carrierServiceId' => ['nullable', 'integer'],

            //with address
            'name' => ['nullable', 'string', 'max:41'],
            'city' => ['nullable', 'string', 'max:30'], 
            'countryId' => ['nullable', 'integer', 'min:0'], 
            'locationGroupId' => ['nullable', 'integer', 'min:0'],
            'addressName' => ['nullable', 'string', 'max:90'],
            'stateId' => ['nullable', 'integer', 'min:0'], 
            'address' => ['nullable', 'string', 'max:90'], 
            'typeID' => ['nullable', 'integer', 'min:0'], 
            'zip' => ['nullable', 'string', 'max:10']
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
