<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class StoreLocationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'location' => ['required', 'string', 'max:30', 'unique:location,name'],
            'description' => ['required', 'string', 'max:90'],
            'type' => ['required', 'string', 'exists:locationtype,name'],
            'locationGroup' => ['required', 'string', 'max:30'],
            'locationNum' => ['nullable', 'numeric'],
            'customerName' => ['nullable', 'string', 'max:30'],
            'active' => ['required', 'boolean'],
            'available' => ['required', 'boolean'],
            'pickable' => ['required', 'boolean'],
            'receivable' => ['required', 'boolean'],
            'sortOrder' => ['nullable', 'numeric'],
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
