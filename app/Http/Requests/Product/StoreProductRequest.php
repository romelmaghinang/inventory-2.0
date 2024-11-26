<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

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
            'partNumber' => ['nullable', 'string', 'exists:part,num'], // partId
            'productNumber' => ['nullable', 'string', 'max:70', 'unique:product,num'], // num
            'productDescription' => ['nullable', 'string', 'max:252'], // description
            'productDetails' => ['required', 'string'], // details
            'uom' => ['required', 'string', 'exists:uom,name'],
            'price' => ['nullable', 'numeric'],
            'class' => ['nullable', 'string'],
            'active' => ['required', 'boolean'], // activeFlag
            'taxable' => ['required', 'boolean'], // taxbableFlag
            'combo' => ['required', 'boolean'], // showSoComboFlag
            'allowUom' => ['required', 'boolean'], // sellableInOtherUoms
            'productUrl' => ['nullable', 'string', 'max:256'], // url
            'productPictureUrl' => ['nullable', 'string', 'max:256'],
            'productUpc' => ['nullable', 'string', 'max:41'], // upc
            'productSku' => ['nullable', 'string', 'max:41'], // sku
            'productSoItemType' => ['required', 'string', 'exists:soitemtype,name'], // defaultSoItemType
            'incomeAccount' => ['required', 'string'],
            'weight' => ['nullable', 'numeric'],
            'weightUom' => ['nullable', 'string'], // weightUom 
            'width' => ['nullable', 'numeric'],
            'height' => ['nullable', 'numeric'],
            'length' => ['nullable', 'numeric'],
            'sizeUom' => ['nullable', 'string'],
            'default' => ['required', 'boolean'],
            'alertNote' => ['nullable', 'string', 'max:90'],
            'cartonCount' => ['required', 'numeric'],
            'cartonType' => ['required', 'string'],
            'cf' => ['required', 'string'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'duplicateFields' => [],
        ];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                }
            }
        }

        $response = response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        throw new HttpResponseException($response);
    }
}
