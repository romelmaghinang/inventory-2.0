<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductRequest extends FormRequest
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
                'productNumber' => ['nullable', 'string', 'max:70', 'unique:product,num,' . $this->route('product')], // num
                'productDescription' => ['nullable', 'string', 'max:252'], // description
                'productDetails' => ['nullable', 'string'], // details
                'uom' => ['nullable', 'string', 'exists:uom,name'],
                'price' => ['nullable', 'numeric'],
                'class' => ['nullable', 'string'],
                'active' => ['nullable', 'boolean'], // activeFlag
                'taxable' => ['nullable', 'boolean'], // taxableFlag
                'combo' => ['nullable', 'boolean'], // showSoComboFlag
                'allowUom' => ['nullable', 'boolean'], // sellableInOtherUoms
                'productUrl' => ['nullable', 'string', 'max:256'], // url
                'productPictureUrl' => ['nullable', 'string', 'max:256'],
                'productUpc' => ['nullable', 'string', 'max:41'], // upc
                'productSku' => ['nullable', 'string', 'max:41'], // sku
                'productSoItemType' => ['nullable', 'string', 'exists:soitemtype,name'], // defaultSoItemType
                'incomeAccount' => ['nullable', 'string'],
                'weight' => ['nullable', 'numeric'],
                'weightUom' => ['nullable', 'string'], // weightUom 
                'width' => ['nullable', 'numeric'],
                'height' => ['nullable', 'numeric'],
                'length' => ['nullable', 'numeric'],
                'sizeUom' => ['nullable', 'string'],
                'default' => ['nullable', 'boolean'],
                'alertNote' => ['nullable', 'string', 'max:90'],
                'cartonCount' => ['nullable', 'numeric'],
                'cartonType' => ['nullable', 'string'],
                'cf' => ['nullable', 'string'],
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
    
        if (empty(array_filter($categorizedErrors))) {
            $categorizedErrors['validationErrors'] = $errors->messages();
        }
    
        $response = response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $categorizedErrors, 
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    
        throw new HttpResponseException($response);
    }
}
