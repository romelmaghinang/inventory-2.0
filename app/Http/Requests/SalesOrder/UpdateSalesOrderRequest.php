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
            // General Information
            'soNum' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer', 'exists:sostatus,id'],
            'customerName' => ['nullable', 'string', 'max:100'],
            'customerContact' => ['nullable', 'string', 'max:30'], 
            'billToName' => ['nullable', 'string', 'max:41'], 
            'billToAddress' => ['nullable', 'string', 'max:90'], 
            'billToCity' => ['nullable', 'string', 'max:30'], 
            'billToState' => ['nullable', 'string', 'exists:state,name'], 
            'billToZip' => ['nullable', 'string', 'max:10'], 
            'billToCountry' => ['nullable', 'string', 'exists:country,name'], 
            'shipToName' => ['nullable', 'string', 'max:41'], 
            'shipToAddress' => ['nullable', 'string', 'max:90'],
            'shipToCity' => ['nullable', 'string', 'max:30'], 
            'shipToState' => ['nullable', 'string', 'exists:state,name'], 
            'shipToZip' => ['nullable', 'string', 'max:10'], 
            'shipToCountry' => ['nullable', 'string', 'exists:country,name'], 
            'shipToResidential' => ['nullable', 'boolean'], 
            'carrierName' => ['nullable', 'string', 'max:100', 'exists:carrier,name'], 
            'carrierService' => ['nullable', 'string', 'max:100', 'exists:carrierservice,name'], 
            'taxRateName' => ['nullable', 'string', 'max:100', 'exists:taxrate,name'],
            'priorityId' => ['nullable', 'integer', 'min:0', 'exists:priority,id'], 
            'poNum' => ['nullable', 'string', 'max:50'], 
            'vendorPONum' => ['nullable', 'string', 'max:25'], 
            'date' => ['nullable', 'date'], 
            'orderDateScheduled' => ['nullable', 'date'], 
            'dateExpired' => ['nullable', 'date'], 
            'salesman' => ['nullable', 'string', 'max:100'], 
            'shippingTerms' => ['nullable', 'integer', 'in:10,20,30'], 
            'paymentTerms' => ['nullable', 'string', 'max:50'], 
            'fob' => ['nullable', 'string', 'max:50'], 
            'note' => ['nullable', 'string', 'max:500'], 
            'quickBookClassName' => ['nullable', 'string', 'exists:qbclass,name'], 
            'locationGroupName' => ['nullable', 'string', 'max:100'], 
            'phone' => ['nullable', 'string', 'max:256'], 
            'email' => ['nullable', 'string', 'max:256', 'email'], 
            'url' => ['nullable', 'string', 'max:256', 'url'], 
            'category' => ['nullable', 'string', 'max:100'], 
            'customField' => ['nullable', 'string', 'max:255'], 
            'currencyName' => ['nullable', 'string', 'max:255', 'exists:currency,name'],
            'currencyRate' => ['nullable', 'numeric'],
            'priceIsHomeCurrency' => ['nullable', 'numeric'],
        
            /*soitem*/
            'items' => ['nullable', 'array'],
            'items.*.flag' => ['nullable', 'boolean'],
            'items.*.soItemTypeId' => ['nullable', 'integer', 'exists:soitemtype,id'], 
            'items.*.productNumber' => ['nullable', 'string', 'max:70', 'exists:product,num'], 
            'items.*.productDescription' => ['nullable', 'string', 'max:256'], 
            'items.*.productQuantity' => ['nullable', 'integer'], 
            'items.*.uom' => ['nullable', 'integer'],
            'items.*.productPrice' => ['nullable', 'numeric'], 
            'items.*.taxable' => ['nullable', 'boolean'],
            'items.*.taxCode' => ['nullable', 'integer'],
            'items.*.note' => ['nullable', 'string'],
            'items.*.itemQuickBooksClassName' => ['nullable', 'integer'],  
            'items.*.itemDateScheduled' => ['nullable', 'date'],
            'items.*.showItem' => ['nullable', 'boolean'],
            'items.*.revisionLevel' => ['nullable', 'string'], 
            'items.*.customerPartNumber' => ['nullable', 'string', 'max:70'],
            'items.*.kitItem' => ['nullable', 'boolean'],
            'items.*.cfi' => ['nullable', 'string'],
        ];
        
    }

    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [];

        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'invalid')) {
                    $categorizedErrors['invalidFormat'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } elseif (str_contains($message, 'already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } else {
                    $categorizedErrors['otherErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }
        }

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
