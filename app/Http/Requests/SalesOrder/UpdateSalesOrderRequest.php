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
            'status' => ['required', 'integer', 'exists:sostatus,id'],
            'customerName' => ['required', 'string', 'max:100'],
            'customerContact' => ['required', 'string', 'max:30'], 
            'billToName' => ['required', 'string', 'max:41'], 
            'billToAddress' => ['required', 'string', 'max:90'], 
            'billToCity' => ['required', 'string', 'max:30'], 
            'billToState' => ['required', 'string', 'exists:state,name'], 
            'billToZip' => ['required', 'string', 'max:10'], 
            'billToCountry' => ['required', 'string', 'exists:country,name'], 
            'shipToName' => ['required', 'string', 'max:41'], 
            'shipToAddress' => ['required', 'string', 'max:90'],
            'shipToCity' => ['required', 'string', 'max:30'], 
            'shipToState' => ['required', 'string', 'exists:state,name'], 
            'shipToZip' => ['required', 'string', 'max:10'], 
            'shipToCountry' => ['required', 'string', 'exists:country,name'], 
            'shipToResidential' => ['required', 'boolean'], 
            'carrierName' => ['required', 'string', 'max:100', 'exists:carrier,name'], 
            'carrierService' => ['required', 'string', 'max:100', 'exists:carrierservice,name'], 
            'taxRateName' => ['required', 'string', 'max:100', 'exists:taxrate,name'],
            'priorityId' => ['required', 'integer', 'min:0', 'exists:priority,id'], 
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
            'quickBookClassName' => ['required', 'string', 'exists:qbclass,name'], 
            'locationGroupName' => ['required', 'string', 'max:100'], 
            'phone' => ['required', 'string', 'max:256'], 
            'email' => ['required', 'string', 'max:256', 'email'], 
            'url' => ['required', 'string', 'max:256', 'url'], 
            'category' => ['required', 'string', 'max:100'], 
            'customField' => ['required', 'string', 'max:255'], 
            'currencyName' => ['required', 'string', 'max:255', 'exists:currency,name'],
            'currencyRate' => ['required', 'numeric'],
            'priceIsHomeCurrency' => ['required', 'numeric'],

            /*soitem*/
            'items' => ['required', 'array'],
            'items.*.flag' => ['required', 'boolean'],
            'items.*.soItemTypeId' => ['required', 'integer', 'exists:soitemtype,id'], 
            'items.*.productNumber' => ['required', 'string', 'max:70', 'exists:product,num'], 
            'items.*.productDescription' => ['required', 'string', 'max:256'], 
            'items.*.productQuantity' => ['required', 'integer'], 
            'items.*.uom' => ['required', 'integer'],
            'items.*.productPrice' => ['required', 'numeric'], 
            'items.*.taxable' => ['required', 'boolean'],
            'items.*.taxCode' => ['required', 'integer'],
            'items.*.note' => ['required', 'string'],
            'items.*.itemQuickBooksClassName' => ['required', 'integer'],  
            'items.*.itemDateScheduled' => ['required', 'date'],
            'items.*.showItem' => ['required', 'boolean'],
            'items.*.revisionLevel' => ['required', 'string'], 
            'items.*.customerPartNumber' => ['required', 'string', 'max:70'],
            'items.*.kitItem' => ['required', 'boolean'],
            'items.*.cfi' => ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'duplicateFields' => [],
            'relatedFieldErrors' => [],
        ];
    
        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = $field;
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
