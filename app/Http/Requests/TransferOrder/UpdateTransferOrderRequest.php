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
            'TO' => 'required|array',
            'TO.TONum' => 'nullable|string',
            'TO.TOType' => 'required|string',
            'TO.Status' => 'nullable|string',
            'TO.FromLocationGroup' => 'required|string',
            'TO.FromAddressName' => 'required|string',
            'TO.FromAddressStreet' => 'required|string',
            'TO.FromAddressCity' => 'required|string',
            'TO.FromAddressZip' => 'required|string',
            'TO.FromAddressCountry' => 'required|string',
            'TO.ToLocationGroup' => 'required|string',
            'TO.ToAddressName' => 'required|string',
            'TO.ToAddressStreet' => 'required|string',
            'TO.ToAddressCity' => 'required|string',
            'TO.ToAddressZip' => 'required|string',
            'TO.ToAddressCountry' => 'required|string',
            'TO.OwnerIsFrom' => 'required|string',
            'TO.CarrierName' => 'required|string',
            'TO.CarrierService' => 'nullable|string',
            'TO.Note' => 'nullable|string',
            'TO.CF' => 'nullable|string',
            'Items' => 'required|array',
            'Items.*.PartNumber' => 'required|string',
            'Items.*.PartQuantity' => 'required|integer',
            'Items.*.UOM' => 'required|string',
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
