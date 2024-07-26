<?php

namespace App\Http\Requests\SalesOrder;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateSalesOrderRequest extends FormRequest
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
            'soNum' => ['nullable', 'integer'],
            'status' => ['required', 'integer'],
            'customerName' => ['nullable', 'string', 'max:100'], 
            'customerContact' => ['required', 'string', 'max:30'], 
            'billToName' => ['required', 'string', 'max:41'], 
            'billToAddress' => ['required', 'string', 'max:90'], 
            'billToCity' => ['required', 'string', 'max:30'], 
            'billToState' => ['required', 'string'], 
            'billToZip' => ['required', 'string', 'max:10'],
            'billToCountry' => ['required', 'string'], 
            'shipToName' => ['required', 'string', 'max:41'], 
            'shipToAddress' => ['required', 'string', 'max:90'], 
            'shipToCity' => ['required', 'string', 'max:30'], 
            'shipToState' => ['required', 'string'], 
            'shipToZip' => ['required', 'string', 'max:10'],
            'shipToCountry' => ['required', 'string'], 
            'shipToResidential' => ['required', 'boolean'], 
            'carrierName' => ['required', 'string', 'max:100'], 
            'carrierService' => ['required', 'string', 'max:100'], 
            'taxRateName' => ['required', 'string', 'max:100'], 
            'priorityId' => ['required', 'integer', 'min:0'], 
            'poNum' => ['required', 'string', 'max:50'],
            'vendorPONum' => ['required', 'string', 'max:25'], 
            'date' => ['required', 'date'], 
            'orderDateScheduled' => ['required', 'date'], 
            'dateExpired' => ['required', 'date'], 
            'salesman' => ['required', 'string', 'max:100'], 
            'shippingTerms' => ['required', 'integer', 'in:10,20,30'], 
            'paymentTerms' => ['required', 'string', 'max:50'], 
            'fob' => ['required', 'string', 'max:50'], 
            'note' => ['required', 'string', 'max:500'], 
            'quickBooksClassName' => ['required', 'integer', 'min:0'], 
            'locationGroupName' => ['required', 'string', 'max:100'], 
            'phone' => ['required', 'string', 'max:256'], 
            'email' => ['required', 'string', 'max:256', 'email'], 
            'url' => ['required', 'string', 'max:256', 'url'], 
            'category' => ['required', 'string', 'max:100'], 
            'customField' => ['required', 'string', 'max:255'], 
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
