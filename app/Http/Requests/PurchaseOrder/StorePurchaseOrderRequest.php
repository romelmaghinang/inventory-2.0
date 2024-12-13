<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'PONum' => 'nullable|string|max:255',
            'Status' => 'required|integer',
            'VendorName' => 'nullable|string|max:255',
            'VendorContact' => 'nullable|string|max:255',
            'RemitToName' => 'nullable|string|max:255',
            'RemitToAddress' => 'nullable|string|max:255',
            'RemitToCity' => 'nullable|string|max:255',
            'RemitToState' => 'nullable|string|max:255',
            'RemitToZip' => 'nullable|string|max:20',
            'RemitToCountry' => 'nullable|string|max:255',
            'ShipToName' => 'nullable|string|max:255',
            'DeliverToName' => 'nullable|string|max:255',
            'ShipToAddress' => 'nullable|string|max:255',
            'ShipToCity' => 'nullable|string|max:255',
            'ShipToState' => 'nullable|string|max:255',
            'ShipToZip' => 'nullable|string|max:20',
            'ShipToCountry' => 'nullable|string|max:255',
            'CarrierName' => 'nullable|string|max:255',
            'CarrierService' => 'nullable|string|max:255',
            'VendorSONum' => 'nullable|string|max:255',
            'CustomerSONum' => 'nullable|string|max:255',
            'CreatedDate' => 'nullable|date',
            'CompletedDate' => 'nullable|date',
            'ConfirmedDate' => 'nullable|date',
            'FulfillmentDate' => 'nullable|date',
            'IssuedDate' => 'nullable|date',
            'Buyer' => 'nullable|string|max:255',
            'ShippingTerms' => 'nullable|string|max:255',
            'PaymentTerms' => 'nullable|string|max:255',
            'FOB' => 'nullable|string|max:255',
            'Note' => 'nullable|string',
            'QuickBooksClassName' => 'nullable|string|max:255',
            'LocationGroupName' => 'nullable|string|max:255',
            'URL' => 'nullable|url|max:255',
            'CurrencyName' => 'nullable|string|max:255',
            'CurrencyRate' => 'required|numeric|min:0',
            'Phone' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:255',
            'CF' => 'nullable|string|max:255',

            'items' => 'required|array|min:1',
            'items.*.POItemTypeID' => 'required|integer',
            'items.*.PartNumber' => 'nullable|string|max:255|exists:part,num',
            'items.*.VendorPartNumber' => 'nullable|string|max:255',
            'items.*.PartQuantity' => 'required|integer|min:0',
            'items.*.FulfilledQuantity' => 'nullable|integer|min:0',
            'items.*.PickedQuantity' => 'nullable|integer|min:0',
            'items.*.UOM' => 'nullable|string|max:255',
            'items.*.PartPrice' => 'required|numeric|min:0',
            'items.*.FulfillmentDate' => 'nullable|date',
            'items.*.RevisionLevel' => 'nullable|string|max:10',
            'items.*.CustomerJob' => 'nullable|string|max:255',
            'items.*.Note' => 'nullable|string|max:255',
            'items.*.QuickBooksClassName' => 'nullable|string|max:255',
            'items.*.CFI' => 'nullable|string|max:255',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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
