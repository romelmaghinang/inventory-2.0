<?php

namespace App\Http\Requests\TransferOrder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateTransferOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorize the request (adjust this if needed)
        return true;
    }

    public function rules(): array
    {
        return [
            'TO' => 'nullable|array',
            'TO.TONum' => 'nullable|string',
            'TO.TOType' => 'nullable|string',
            'TO.Status' => 'nullable|string',
            'TO.FromLocationGroup' => 'nullable|string',
            'TO.FromAddressName' => 'nullable|string',
            'TO.FromAddressStreet' => 'nullable|string',
            'TO.FromAddressCity' => 'nullable|string',
            'TO.FromAddressZip' => 'nullable|string',
            'TO.FromAddressCountry' => 'nullable|string',
            'TO.ToLocationGroup' => 'nullable|string',
            'TO.ToAddressName' => 'nullable|string',
            'TO.ToAddressStreet' => 'nullable|string',
            'TO.ToAddressCity' => 'nullable|string',
            'TO.ToAddressZip' => 'nullable|string',
            'TO.ToAddressCountry' => 'nullable|string',
            'TO.OwnerIsFrom' => 'nullable|string',
            'TO.CarrierName' => 'nullable|string',
            'TO.CarrierService' => 'nullable|string',
            'TO.Note' => 'nullable|string',
            'TO.CF' => 'nullable|string',
            'Items' => 'nullable|array',
            'Items.*.PartNumber' => 'nullable|string',
            'Items.*.PartQuantity' => 'nullable|integer',
            'Items.*.UOM' => 'nullable|string',
            'Items.*.Note' => 'nullable|string',
        ];
        
    }

    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [];

        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'invalid')) {
                    $categorizedErrors['invalidFormat'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } elseif (str_contains($message, 'already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                } else {
                    $categorizedErrors['otherErrors'][] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }
        }

        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors),
            ],
            422
        ));
    }
}
