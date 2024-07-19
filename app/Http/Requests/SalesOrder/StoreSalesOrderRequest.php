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
            'billToAddress' => ['string', 'nullable', 'max:90'],
            'billToCity' => ['string', 'nullable', 'max:30'],
            'billToCountryName' => ['nullable', 'integer', 'min:0'], //Country ID EXCEPT
            'billToName' => ['string', 'nullable', 'max:41'],
            'billToStateName' => ['nullable', 'integer', 'min:0'], //State ID EXCEPT
            'billToZip' => ['string', 'nullable', 'max:10'],

            'dateCompleted' => ['nullable', 'date'],
            'dateCreated' => ['nullable', 'date'],
            'dateExpired' => ['nullable', 'date'],
            'dateFirstShip' => ['nullable', 'date'],
            'dateIssued' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'dateRevision' => ['nullable', 'date'],
            'email' => ['string', 'nullable', 'max:256', 'email'],
            'customField' => ['string', 'nullable', 'max:255'],

            'phone' => ['string', 'nullable', 'max:256'],

            'priorityName' => ['nullable', 'integer', 'min:0'], // priorityID EXCEPT
            'quickBookName' => ['nullable', 'integer', 'min:0'], // qbClassId EXCEPT

            'residentialFlag' => ['boolean'],
            'revisionNum' => ['nullable', 'integer'],

            'salesmanId' => ['nullable', 'integer', 'min:0'],
            'salesmanFirstName' => ['string', 'nullable', 'max:30'],
            'salesmanLastName' => ['string', 'nullable', 'max:5'],

            'shipTermsId' => ['nullable', 'integer', 'in:10,20,30'],

            'shipToAddress' => ['string', 'nullable', 'max:90'],
            'shipToCity' => ['string', 'nullable', 'max:30'],
            'shipToCountryName' => ['nullable', 'integer', 'min:0'], //Country ID EXCEPT  shipToCountryID
            'shipToName' => ['string', 'nullable', 'max:41'],
            'shipToStateName' => ['nullable', 'integer', 'min:0'], //State ID EXCEPT shipToStateID
            'shipToZip' => ['string', 'nullable', 'max:10'],

            'status' => ['nullable', 'integer'],

            'toBeEmailed' => ['boolean'],
            'toBePrinted' => ['boolean'],
            'subTotal' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'totalPrice' => ['nullable', 'numeric', 'between:0,999999999999999999.999999999'],
            'typeId' => ['nullable', 'integer', 'min:0'],
            'url' => ['string', 'nullable', 'max:256', 'url'],
            'username' => ['string', 'nullable', 'max:30'],
            'vendorPO' => ['string', 'nullable', 'max:25'],

            'accountTypeName' => ['string', 'nullable', 'max:50'],

            'taxExempt' => ['boolean', 'required'],
            'customerContact' => ['string', 'nullable', 'max:30'],
            'customerPO' => ['string', 'nullable', 'max:25'],

            'soItemTypeName' => ['required', 'integer', 'min:0'],
            'productDetails' => ['required', 'string', 'max:255'],
        ];
    }
}
