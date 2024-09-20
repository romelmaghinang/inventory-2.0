<?php
namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true if everyone is authorized to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'PartNumber' => 'required|string|max:255',
            'PartDescription' => 'required|string|max:255',
            'Location' => 'required|string|max:255',
            'Qty' => 'required|integer',
            'UOM' => 'required|string|max:50',
            'Cost' => 'required|numeric',
            'QbClass' => 'required|string|max:255',
            'Date' => 'nullable|date',
            'Note' => 'nullable|string',
        ];
    }
}
