<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'activeFlag' => ['boolean'],
            'alertNote' => ['nullable', 'string', 'max:90'],
            'dateCreated' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'defaultSoItemType' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:252'],
            'details' => ['required', 'string'],
            'displayTypeId' => ['required', 'integer'],
            'heigh' => ['nullable', 'numeric'],
            'incomeAccountId' => ['required', 'integer'],
            'kitFlag' => ['boolean'],
            'kitGroupedFlag' => ['boolean'],
            'len' => ['nullable', 'numeric'],
            'num' => ['nullable', 'string', 'max:70', 'unique:product,num'],
            'partId' => ['nullable', 'integer'],
            'price' => ['nullable', 'numeric'],
            'qbClassId' => ['nullable', 'integer'],
            'sellableInOtherUoms' => ['boolean'],
            'showSoComboFlag' => ['boolean'],
            'sizeUomId' => ['nullable', 'integer'],
            'sku' => ['nullable', 'string', 'max:41'],
            'taxId' => ['nullable', 'integer'],
            'taxableFlag' => ['boolean'],
            'uomId' => ['required', 'integer'],
            'upc' => ['nullable', 'string', 'max:41'],
            'url' => ['nullable', 'string', 'max:256'],
            'usePriceFlag' => ['boolean'],
            'weight' => ['nullable', 'numeric'],
            'weightUomId' => ['nullable', 'integer'],
            'width' => ['nullable', 'numeric'],
        ];
    }
}
