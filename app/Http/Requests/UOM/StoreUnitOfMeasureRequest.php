<?php

namespace App\Http\Requests\UOM;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitOfMeasureRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:uom,name'],
            'details' => ['required', 'string'],
            'abbrev' => ['required', 'string'],
            'readOnly' => ['required', 'boolean'],
            'active' => ['required','boolean'],
            'uomTypeId'=> ['required','integer ', 'exists:uomtype,id'],
        ];
    }
}
