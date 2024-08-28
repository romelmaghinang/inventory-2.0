<?php

namespace App\Http\Requests\Pick;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StorePickRequest extends FormRequest
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
            'dateCreated' => ['nullable', 'date'],
            'dateFinished' => ['nullable', 'date'],
            'dateLastModified' => ['nullable', 'date'],
            'dateScheduled' => ['nullable', 'date'],
            'dateStarted' => ['nullable', 'date'],
            'num' => ['required', 'string', 'max:35'],
            'locationGroupId' => ['required', 'integer',], // 'exists:locationgroup,id'
            'priority' => ['required', 'integer', 'exists:priority,id'],
            'pickStatusId' => ['required', 'integer', 'exists:pickstatus,id'], // statusId
            'pickTypeId' => ['required', 'integer', 'exists:picktype,id'],
            'userId' => ['required', 'integer',], // 'exists:sysuser,id'

            'items.*.destTagId' => ['nullable', 'integer'],  
            'items.*.orderId' => ['required', 'integer'],
            'items.*.orderTypeId' => ['required', 'integer', 'exists:ordertype,id'],
            'items.*.partId' => ['required', 'integer', 'exists:part,id'],
            'items.*.poItemId' => ['nullable', 'integer', 'exists:poitem,id'],
            'items.*.qty' => ['nullable', 'numeric'],  // decimal(28,9)
            'items.*.shipId' => ['nullable', 'integer'],
            'items.*.slotNum' => ['nullable', 'integer'],
            'items.*.soItemId' => ['nullable', 'integer', 'exists:soitem,id'],
            'items.*.srcLocationId' => ['nullable', 'integer'],
            'items.*.srcTagId' => ['nullable', 'integer'],  
            'items.*.pickItemStatusId' => ['required', 'integer', 'exists:pickitemstatus,id'], 
            'items.*.tagId' => ['nullable', 'integer'],  
            'items.*.pickItemTypeId' => ['required', 'integer', 'exists:pickitemtype,id'], 
            'items.*.uomId' => ['required', 'integer', 'exists:uom,id'],
            'items.*.woItemId' => ['nullable', 'integer', 'exists:woitem,id'],
            'items.*.xoItemId' => ['nullable', 'integer', 'exists:xoitem,id'],
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
