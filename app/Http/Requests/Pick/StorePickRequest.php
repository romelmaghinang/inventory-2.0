<?php

namespace App\Http\Requests\Pick;

use Illuminate\Foundation\Http\FormRequest;

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
            'locationGroupId' => ['required', 'integer', ], // 'exists:locationgroup,id'
            'priority' => ['required', 'integer', 'exists:priority,id'],
            'pickStatusId' => ['required', 'integer', 'exists:pickstatus,id'], 
            'pickTypeId' => ['required', 'integer', 'exists:picktype,id'], 
            'userId' => ['required', 'integer',], // 'exists:sysuser,id'

            'destTagId' => ['nullable', 'integer'],  // bigint
            'orderId' => ['required', 'integer'],
            'orderTypeId' => ['required', 'integer', 'exists:ordertype,id'],
            'partId' => ['required', 'integer', 'exists:part,id'],
            'poItemId' => ['nullable', 'integer', 'exists:poitem,id'], 
            'qty' => ['nullable', 'numeric',],  // decimal(28,9)
            'shipId' => ['nullable', 'integer',],
            'slotNum' => ['nullable', 'integer',],
            'soItemId' => ['nullable', 'integer', 'exists:soitem,id'],
            'srcLocationId' => ['nullable', 'integer',],
            'srcTagId' => ['nullable', 'integer',],  // bigint
            'pickItemStatusId' => ['required', 'integer', 'exists:pickitemstatus,id'], // statusId
            'tagId' => ['nullable', 'integer',],  // bigint
            'pickItemTypeId' => ['required', 'integer', 'exists:pickitemtype,id'], // typeId
            'uomId' => ['required', 'integer', 'exists:uom,id'],
            'woItemId' => ['nullable', 'integer', 'exists:woitem,id'],
            'xoItemId' => ['nullable', 'integer', 'exists:xoitem,id'],
        ];
    }
}
