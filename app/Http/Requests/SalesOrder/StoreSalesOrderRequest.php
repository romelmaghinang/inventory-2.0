<?php

namespace App\Http\Requests\SalesOrder;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
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
            'status' => ['required', 'in:10,20,25,60,70,80,85,90,95'],
            'customerName' => ['required', 'string', 'max:255'],
            'customerContact' => ['nullable', 'string', 'max:255'],
            'billToAddress' => ['required', 'string', 'max:255'],
            'billToCity' => ['required', 'string', 'max:255'],
            'billToName' => ['required', 'string', 'max:255'],
            'billToZip' => ['required', 'string', 'max:20'],
            'dateFirstShip' => ['required', 'date'],
            'shipToAddress' => ['required', 'string', 'max:255'],
            'shipToCity' => ['required', 'string', 'max:255'],
            'shipToName' => ['required', 'string', 'max:255'],
            'shipToZip' => ['required', 'string', 'max:20'],
            'taxRateName' => ['required', 'string', 'max:255'],
            'accountName' => ['required', 'string', 'max:255'],
            'countryName' => ['required', 'string', 'max:255'],
            'stateName' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric'],
            'taxExempt' => ['required', 'boolean'],
            'toBeEmailed' => ['required', 'boolean'],
            'toBePrinted' => ['required', 'boolean'],
            'url' => ['nullable', 'url'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
