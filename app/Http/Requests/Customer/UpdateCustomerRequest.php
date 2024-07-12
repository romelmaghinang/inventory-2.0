<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'accountId' => ['required', 'integer'],
            'accountingHash' => ['nullable', 'string', 'max:30'],
            'accountingId' => ['nullable', 'string', 'max:36'],
            'activeFlag' => ['nullable', 'boolean'],
            'creditLimit' => ['nullable', 'numeric'],
            'currencyId' => ['nullable', 'integer'],
            'currencyRate' => ['nullable', 'numeric'],
            'dateCreated' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'defaultCarrierId' => ['nullable', 'integer'],
            'defaultPaymentTermsId' => ['nullable', 'integer'],
            'defaultSalesmanId' => ['required', 'integer'],
            'defaultShipTermsId' => ['nullable', 'integer'],
            'jobDepth' => ['nullable', 'integer'],
            'lastChangedUser' => ['nullable', 'string', 'max:15'],
            'name' => ['required', 'string', 'max:41'],
            'note' => ['nullable', 'string', 'max:90'],
            'number' => ['nullable', 'string', 'max:30'],
            'parentId' => ['nullable', 'integer'],
            'pipelineAccountNum' => ['nullable', 'integer'],
            'qbClassId' => ['nullable', 'integer'],
            'statusId' => ['required', 'integer'],
            'sysUserId' => ['nullable', 'integer'],
            'taxExempt' => ['required', 'boolean'],
            'taxExemptNumber' => ['nullable', 'string', 'max:30'],
            'taxRateId' => ['nullable', 'integer'],
            'toBeEmailed' => ['required', 'boolean'],
            'toBePrinted' => ['required', 'boolean'],
            'url' => ['nullable', 'url', 'max:30'],
            'issuableStatusId' => ['nullable', 'integer'],
            'carrierServiceId' => ['nullable', 'integer'],
        ];
    }
}
