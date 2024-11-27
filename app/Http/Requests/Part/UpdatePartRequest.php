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
        'activeFlag' => ['nullable', 'boolean'],
        'alertNote' => ['nullable', 'string', 'max:256'],
        'alwaysManufacture' => ['nullable', 'boolean'],
        'configurable' => ['nullable', 'boolean'],
        'consumptionRate' => ['nullable', 'numeric'],
        'controlledFlag' => ['nullable', 'boolean'],
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
        'num' => ['nullable', 'string', 'max:70'],
        'partClassId' => ['nullable', 'integer'],
        'pickInUomOfPart' => ['nullable', 'boolean'],
        'receivingTol' => ['nullable', 'numeric'],
        'revision' => ['nullable', 'string', 'max:15'],
        'scrapAccountId' => ['nullable', 'integer'],
        'serializedFlag' => ['nullable', 'boolean'],
        'sizeUomId' => ['nullable', 'integer'],
        'stdCost' => ['nullable', 'numeric'],
        'taxId' => ['nullable', 'integer'],
        'trackingFlag' => ['nullable', 'boolean'],
        'typeId' => ['nullable', 'integer'],
        'uomId' => ['nullable', 'integer'],
        'upc' => ['nullable', 'string', 'max:31'],
        'url' => ['nullable', 'string', 'max:256', 'url'],
        'varianceAccountId' => ['nullable', 'integer'],
        'weight' => ['nullable', 'numeric'],
        'weightUomId' => ['nullable', 'integer'],
        'width' => ['nullable', 'numeric'],
        'customFields' => ['nullable'],

        // PRODUCT 
        'defaultSoItemType' => ['nullable', 'integer'],
        'displayTypeId' => ['nullable', 'integer'],
        'heigh' => ['nullable', 'numeric'],
        'incomeAccountId' => ['nullable', 'integer'],
        'kitFlag' => ['nullable', 'boolean'],
        'kitGroupedFlag' => ['nullable', 'boolean'],
        'price' => ['nullable', 'numeric'],
        'qbClassId' => ['nullable', 'integer'],
        'sellableInOtherUoms' => ['nullable', 'boolean'],
        'showSoComboFlag' => ['nullable', 'boolean'],
        'sku' => ['nullable', 'string', 'max:41'],
        'taxableFlag' => ['nullable', 'boolean'],
        'usePriceFlag' => ['nullable', 'boolean'],

        ];
    }

    /**
     * Handle failed validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'duplicateFields' => [],
            'relatedFieldErrors' => [],
        ];

        foreach ($validator->errors()->toArray() as $field => $messages) {
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
