<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdatePartRequest extends FormRequest
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
            'abcCode' => ['nullable', 'string', 'max:1'],
            'accountingHash' => ['nullable', 'string', 'max:30'],
            'accountingId' => ['nullable', 'string', 'max:36'],
            'activeFlag' => ['required', 'boolean'],
            'alertNote' => ['nullable', 'string', 'max:256'],
            'alwaysManufacture' => ['required', 'boolean'],
            'configurable' => ['required', 'boolean'],
            'consumptionRate' => ['required', 'numeric'],
            'controlledFlag' => ['required', 'boolean'],
            'cycleCountTol' => ['nullable', 'numeric'],
            'dateCreated' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'defaultBomId' => ['nullable', 'integer'],
            'defaultOutsourcedReturnItemId' => ['nullable', 'integer'],
            'defaultPoItemTypeId' => ['nullable', 'integer'],
            'defaultProductId' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:252'],
            'details' => ['nullable', 'string'],
            'height' => ['nullable', 'numeric'],
            'inventoryAccountId' => ['nullable', 'integer'],
            'lastChangedUser' => ['nullable', 'string', 'max:100'],
            'leadTime' => ['nullable', 'integer'],
            'len' => ['nullable', 'numeric'],
            'num' => ['required', 'string', 'max:70'],
            'partClassId' => ['nullable', 'integer'],
            'pickInUomOfPart' => ['required', 'boolean'],
            'receivingTol' => ['nullable', 'numeric'],
            'revision' => ['nullable', 'string', 'max:15'],
            'scrapAccountId' => ['nullable', 'integer'],
            'serializedFlag' => ['required', 'boolean'],
            'sizeUomId' => ['nullable', 'integer'],
            'stdCost' => ['nullable', 'numeric'],
            'taxId' => ['nullable', 'integer'],
            'trackingFlag' => ['required', 'boolean'],
            'typeId' => ['required', 'integer'],
            'uomId' => ['required', 'integer'],
            'upc' => ['nullable', 'string', 'max:31'],
            'url' => ['nullable', 'string', 'max:256', 'url'],
            'varianceAccountId' => ['nullable', 'integer'],
            'weight' => ['nullable', 'numeric'],
            'weightUomId' => ['nullable', 'integer'],
            'width' => ['nullable', 'numeric'],
            'customFields' => ['nullable'],

            // PRODUCT 
            'defaultSoItemType' => ['required', 'integer'],
            'displayTypeId' => ['required', 'integer'],
            'heigh' => ['nullable', 'numeric'],
            'incomeAccountId' => ['required', 'integer'],
            'kitFlag' => ['boolean'],
            'kitGroupedFlag' => ['boolean'],
            'price' => ['nullable', 'numeric'],
            'qbClassId' => ['nullable', 'integer'],
            'sellableInOtherUoms' => ['boolean'],
            'showSoComboFlag' => ['boolean'],
            'sku' => ['nullable', 'string', 'max:41'],
            'taxableFlag' => ['boolean'],
            'usePriceFlag' => ['boolean'],
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
