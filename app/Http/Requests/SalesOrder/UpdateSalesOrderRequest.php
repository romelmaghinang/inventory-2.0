<?php

namespace App\Http\Requests\SalesOrder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:10,20,95'],
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
        ];
    }
}
