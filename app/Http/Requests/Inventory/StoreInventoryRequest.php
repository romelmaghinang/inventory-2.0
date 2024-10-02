<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Symfony\Component\HttpFoundation\Response; 

class StoreInventoryRequest extends FormRequest
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
            'PartNumber' => ['required', 'string', 'max:70', 'exists:part,num',
            'unique:part,num' ],
            'PartDescription' => ['nullable', 'string', 'max:252'],
            'Location' => ['required', 'string', 'exists:location,name'],
            'Qty' => ['required', 'numeric'],
            'UOM' => ['required', 'string', 'exists:uom,name'],
            'Cost' => ['required', 'numeric', 'max:9999999999'],
            'QbClass' => ['nullable', 'string', 'exists:qbclass,name'],
            'Date' => ['nullable', 'date'],
            'Note' => ['nullable', 'string'],
            'TrackingType' => ['nullable', 'string', 'exists:parttrackingtype,name'], 
      ];
    }

    /**
     * Handle a failed validation attempt.
     */
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
