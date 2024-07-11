<?php

namespace App\Http\Requests\SalesOrder;

use Illuminate\Foundation\Http\FormRequest;

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
            // BILL TO ADDRESS CONTROLLER
            'billToAddress' => ['string', 'nullable', 'max:90'],
            'billToCity' => ['string', 'nullable', 'max:30'],
            'billToCountryName' => ['nullable', 'integer', 'min:0'], //Country ID EXCEPT 
            'billToName' => ['string', 'nullable', 'max:41'],
            'billToStateName' => ['nullable', 'integer', 'min:0'], //State ID EXCEPT
            'billToZip' => ['string', 'nullable', 'max:10'],

            // CARRIER
            'carrierServiceName' => ['nullable', 'integer', 'min:0'],
            'cost' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            //CURRENCY
            'currencyName' => ['nullable', 'integer', 'min:0'], // Currency ID
            'currencyRate' => ['nullable', 'numeric'],

            'customerContact' => ['string', 'nullable', 'max:30'],
            'customerPO' => ['string', 'nullable', 'max:25'],
            'dateCompleted' => ['nullable', 'date'],
            'dateCreated' => ['nullable', 'date'],
            'dateExpired' => ['nullable', 'date'],
            'dateFirstShip' => ['nullable', 'date'],
            'dateIssued' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'dateRevision' => ['nullable', 'date'],
            'email' => ['string', 'nullable', 'max:256', 'email'],
            'estimatedTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],

            'locationGroupName' => ['nullable', 'integer', 'min:0'], // locationGroupId EXCEPT
            'mcTotalTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'note' => ['nullable', 'string'],
            'num' => ['string', 'nullable', 'max:25'],

            'paymentTermsName' => ['nullable', 'integer', 'min:0'], // paymentTermsID but the name is going to PaymentTermsType EXCEPT
            'phone' => ['string', 'nullable', 'max:256'],

            'priorityName' => ['nullable', 'integer', 'min:0'], // priorityID EXCEPT
            'quickBookName' => ['nullable', 'integer', 'min:0'], // qbClassId EXCEPT
            'residentialFlag' => ['boolean'],
            'revisionNum' => ['nullable', 'integer'],

            // SalesMan
            'salesmanId' => ['nullable', 'integer', 'min:0'],
            'salesmanFirstName' => ['string', 'nullable', 'max:30'],
            'salesmanLastName' => ['string', 'nullable', 'max:5'],
            
            'shipTermsId' => ['nullable', 'integer', 'in:10,20,30'],

            // ShipTo
            'shipToAddress' => ['string', 'nullable', 'max:90'],
            'shipToCity' => ['string', 'nullable', 'max:30'],
            'shipToCountryName' => ['nullable', 'integer', 'min:0'], //Country ID EXCEPT  shipToCountryID
            'shipToName' => ['string', 'nullable', 'max:41'],
            'shipToStateName' => ['nullable', 'integer', 'min:0'], //State ID EXCEPT shipToStateID
            'shipToZip' => ['string', 'nullable', 'max:10'],

            'status' => ['nullable', 'integer'],

            'taxRate' => ['nullable', 'numeric'],
            'taxRateName' => ['string', 'nullable', 'max:31'], // Tax Rate ID 

            'toBeEmailed' => ['boolean'],
            'toBePrinted' => ['boolean'],
            'totalIncludesTax' => ['boolean'],
            'totalTax' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'subTotal' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'totalPrice' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'typeId' => ['nullable', 'integer', 'min:0'],
            'url' => ['string', 'nullable', 'max:256', 'url'],
            'username' => ['string', 'nullable', 'max:30'],
            'vendorPO' => ['string', 'nullable', 'max:25'],

            // LOCATION REQUEST
            'activeFlag' => ['boolean'],
            'countedAsAvailable' => ['boolean'],
            'defaultFlag' => ['boolean'],
            'locationName' => ['string', 'nullable', 'max:50'], 
            'pickable' => ['boolean'],
            'receivable' => ['boolean', 'required'],
            'sortOrder' => ['integer', 'nullable', 'min:0', 'max:9999'],

            // ACCOUNT TYPE
            'accountTypeName' => ['string', 'nullable', 'max:50'],

            // CUSTOMER CONTROLLER
            'customerName' => ['string', 'max:50', 'required'],
            'taxExempt' => ['boolean', 'required'],

            // PRODUCT CONTROLLER
            'soItemTypeName' => ['required', 'integer', 'min:0'],
            'productDetails' => ['required', 'string', 'max:255'],
            
            // SALES ORDER ITEM TYPE
            'salesOrderItemTypeName' => ['required', 'string', 'max:30'],

            // SALES ORDER ITEM
            'note' => ['string', 'nullable', 'max:255'],
            'salesOrderLineItem' => ['int', 'nullable', 'max:255'],
            'salesOrderStatus' => ['required', 'string', 'max:30'],
        ];
    }
}
