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
            'partNumber' => ['required', 'string', 'max:70', 'unique:part,num'], // num
            'partDescription' => ['required', 'string', 'max:252'], // description
            'partDetails' => ['required', 'string'],
            'uom' => ['required', 'string', 'exists:uom,name'], // uomId
            'upc' => ['required', 'string', 'max:31'],
            'partType' => ['required', 'string', 'exists:parttype,name'], // typeId
            'active' => ['required', 'boolean'], // active Flag
            'abcCode' => ['required', 'string', 'max:1'],
            'weight' => ['required', 'numeric'],
            'weightUom' => ['required', 'integer'], // weightuomId
            'width' => ['required', 'numeric'],
            'length' => ['required', 'numeric'], // lenght
            'sizeUom' => ['required', 'integer'], // size Uom Id
            'consumptionRate' => ['required', 'numeric'],
            'alertNote' => ['required', 'string', 'max:256'],
            'pictureUrl' => ['required', 'string', 'max:256', 'url'], // url
            'revision' => ['required', 'string', 'max:15'],
            'poItemType' => ['required', 'string', 'exists:poitemtype,name'], // defualtPoItemTypeId
            'defaultOutsourcedReturnItem' => ['required', 'integer'], // defaultOutsourcedReturnItemId
            'primaryTracking' => ['required', 'string'],
            'tracks' => ['required', 'string'],
            'nextValue' => ['required', 'string'],
            'cf' => ['required', 'string'], // customFields
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
