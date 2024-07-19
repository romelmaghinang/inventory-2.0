<?php

namespace App\Http\Requests\SalesOrderItem;

use Illuminate\Foundation\Http\FormRequest;

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
            // SALES ORDER ITEM TYPE
            'salesOrderItemTypeName' => ['required', 'string', 'max:30'],

            // SALES ORDER ITEM
            'note' => ['string', 'nullable', 'max:255'],
            'salesOrderLineItem' => ['int', 'nullable', 'max:255'],
            'salesOrderStatus' => ['required', 'string', 'max:30'],
        ];
    }
}
