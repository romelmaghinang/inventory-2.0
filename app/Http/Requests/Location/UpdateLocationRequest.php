<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'locationGroupName' => ['nullable', 'integer', 'min:0'], // locationGroupId EXCEPT
            'activeFlag' => ['boolean'],
            'countedAsAvailable' => ['boolean'],
            'locationName' => ['string', 'nullable', 'max:50'],
            'pickable' => ['boolean'],
            'receivable' => ['boolean', 'required'],
            'sortOrder' => ['integer', 'nullable', 'min:0', 'max:9999'],
        ];
    }
}
