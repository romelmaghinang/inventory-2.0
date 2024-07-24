<?php

namespace App\Http\Requests\SalesOrderItem;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateSalesOrderItemRequest extends FormRequest
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
            'items.*.adjustAmount' => ['nullable', 'numeric'],
            'items.*.adjustPercentage' => ['nullable', 'numeric'],
            'items.*.customerPartNum' => ['nullable', 'string'],
            'items.*.dateLastFulfillment' => ['nullable', 'date'],
            'items.*.dateScheduledFulfillment' => ['nullable', 'date'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.exchangeSOLineItem' => ['nullable', 'integer'],
            'items.*.itemAdjustId' => ['nullable', 'integer'],
            'items.*.markupCost' => ['nullable', 'numeric'],
            'items.*.mcTotalPrice' => ['nullable', 'numeric'],
            'items.*.note' => ['nullable', 'string'],
            'items.*.productId' => ['required', 'integer'],
            'items.*.productNum' => ['nullable', 'string'],
            'items.*.qtyFulfilled' => ['nullable', 'integer'],
            'items.*.qtyOrdered' => ['required', 'integer'],
            'items.*.qtyPicked' => ['nullable', 'integer'],
            'items.*.qtyToFulfill' => ['nullable', 'integer'],
            'items.*.revLevel' => ['nullable', 'string'],
            'items.*.showItemFlag' => ['nullable', 'boolean'],
            'items.*.soLineItem' => ['nullable', 'integer'],
            'items.*.taxId' => ['nullable', 'integer'],
            'items.*.taxableFlag' => ['nullable', 'boolean'],
            'items.*.totalCost' => ['nullable', 'numeric'],
            'items.*.typeId' => ['nullable', 'integer'],
            'items.*.unitPrice' => ['required', 'numeric'],
            'items.*.uomId' => ['nullable', 'integer']
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
