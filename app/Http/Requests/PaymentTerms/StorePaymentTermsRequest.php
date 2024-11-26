<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class StorePaymentTermsRequest extends FormRequest
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
            'termsName' => ['required', 'string', 'unique:paymentterms,name'], // name
            'termsType' => ['required', 'string', 'exists:paymenttermstype,name'], // typeId
            'netDays' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'discountDays' => ['required', 'numeric'],
            'dueDate' => ['required', 'date'],
            'nextMonth' => ['required', 'date'],
            'discountDate' => ['required', 'date'],
            'default' => ['required', 'boolean'], // defaultTerms
            'active' => ['required', 'boolean'], // activeFlag
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        $categorizedErrors = [
            'missingRequiredFields' => [],
            'invalidFormat' => [],
            'valueConflict' => [],
        ];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'already been taken') || str_contains($message, 'exists')) {
                    $categorizedErrors['valueConflict'][] = $field;
                }
            }
        }

        $response = new JsonResponse([
            'success' => false,
            'message' => 'Validation errors occurred.',
            'errors' => array_filter($categorizedErrors), 
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
