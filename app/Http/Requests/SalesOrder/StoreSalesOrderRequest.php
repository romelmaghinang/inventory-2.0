<?php

namespace App\Http\Requests\SalesOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreSalesOrderRequest extends FormRequest
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
    public function rules()
    {
        return [
                'flag' => ['boolean'],
                'status' => ['integer'], 
                'customerName' => ['required', 'string', 'max:100'], 
                'customerContact' => ['string', 'required', 'max:30'], 
                'billToName' => ['string', 'required', 'max:41'],
                'billToAddress' => ['string', 'required', 'max:90'],
                'billToCity' => ['string', 'required', 'max:30'],
                'billToState' => ['required', 'string', 'min:0'],
                'billToZip' => ['string', 'required', 'max:10'],
                'billToCountry' => ['required', 'integer', 'min:0'], 
                'shipToName' => ['string', 'required', 'max:41'],
                'shipToAddress' => ['string', 'required', 'max:90'],
                'shipToCity' => ['string', 'required', 'max:30'],
                'shipToState' => ['required', 'string', 'min:0'],
                'shipToZip' => ['string', 'required', 'max:10'],
                'shipToCountry' => ['required', 'integer', 'min:0'],
                'shipToResidential' => ['boolean'],
                'carrierName' => ['required', 'string', 'max:100'],
                'carrierService' => ['nullable', 'string', 'max:100'],
                'taxRateName' => ['required', 'string', 'max:100'], 
                'priorityId' => ['nullable', 'integer', 'min:0'],
                'poNum' => ['nullable', 'string', 'max:50'], 
                'vendorPONum' => ['string', 'nullable', 'max:25'], 
                'date' => ['nullable', 'date'],
                'orderDateScheduled' => ['nullable', 'date'],
                'dateExpired' => ['nullable', 'date'],
                'salesman' => ['nullable', 'string', 'max:100'],
                'shippingTerms' => ['nullable', 'integer', 'in:10,20,30'],
                'paymentTerms' => ['nullable', 'string', 'max:50'],
                'fob' => ['nullable', 'string', 'max:50'], 
                'note' => ['nullable', 'string', 'max:500'],
                'quickBooksClassName' => ['nullable', 'integer', 'min:0'], 
                'locationGroupName' => ['nullable', 'string', 'max:100'],
                'phone' => ['string', 'nullable', 'max:256'], 
                'email' => ['string', 'nullable', 'max:256', 'email'], 
                'url' => ['string', 'nullable', 'max:256', 'url'], 
                'category' => ['nullable', 'string', 'max:100'], 
                'customField' => ['string', 'nullable', 'max:255']
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
