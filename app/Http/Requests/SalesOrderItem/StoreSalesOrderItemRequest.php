<?php

namespace App\Http\Requests\SalesOrderItem;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreSalesOrderItemRequest extends FormRequest
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
            'items' => ['required', 'array'],
            'items.*.Flag' => ['required', 'boolean'],
            'items.*.productNum' => ['nullable', 'string', 'max:70'],
            'items.*.description' => ['nullable', 'string', 'max:256'],
            'items.*.qtyOrdered' => ['required', 'integer'],
            'items.*.uomId' => ['nullable', 'integer'],
            'items.*.unitPrice' => ['nullable', 'numeric', 'digits_between:1,28'],
            'items.*.taxableFlag' => ['required', 'boolean'],
            'items.*.taxRateCode' => ['required', 'integer'],
            'items.*.note' => ['required', 'string'],
            'items.*.qbClassId' => ['nullable', 'integer'],
            'items.*.dateScheduledFulfillment' => ['required', 'date'],
            'items.*.showItemFlag' => ['required', 'boolean'],
            'items.*.typeId' => ['required', 'integer'],
            'items.*.revLevel' => ['required', 'string'],
            'items.*.customerPartNum' => ['nullable', 'string', 'max:70'],
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
