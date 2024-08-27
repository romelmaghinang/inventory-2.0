<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StorePartRequest extends FormRequest
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
            'partNumber' => ['required', 'string', 'max:70', 'unique:part,num'], 
            'partDescription' => ['required', 'string', 'max:252'], 
            'partDetails' => ['required', 'string'],
            'uom' => ['required', 'string', 'exists:uom,name'],
            'upc' => ['required', 'string', 'max:31'],
            'partType' => ['required', 'string', 'exists:parttype,name'],
            'active' => ['required', 'boolean'],
            'abcCode' => ['required', 'string', 'max:1'],
            'weight' => ['required', 'numeric'],
            'weightUom' => ['required', 'integer'],
            'width' => ['required', 'numeric'],
            'length' => ['required', 'numeric'], 
            'sizeUom' => ['required', 'integer'],
            'consumptionRate' => ['required', 'numeric'],
            'alertNote' => ['required', 'string', 'max:256'],
            'pictureUrl' => ['required', 'string', 'max:256', 'url'],
            'revision' => ['required', 'string', 'max:15'],
            'poItemType' => ['required', 'string', 'exists:poitemtype,name'], 
            'defaultOutsourcedReturnItem' => ['required', 'integer'], 
            'primaryTracking' => ['required', 'string'],
            'tracks' => ['required', 'string', 'exists:parttrackingtype,name'],
            'nextValue' => ['required', 'string'],
            'cf' => ['required', 'string'], 
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
