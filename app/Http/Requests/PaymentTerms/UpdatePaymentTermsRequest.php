<?php

namespace App\Http\Requests\PaymentTerms;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdatePaymentTermsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Adjust this as necessary to check user permissions.
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
            'termsName' => ['nullable', 'string', 'unique:paymentterms,name,' . $this->route('payment_term')], 
            'termsType' => ['nullable', 'string', 'exists:paymenttermstype,name'], // typeId
            'netDays' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'discountDays' => ['nullable', 'numeric'],
            'dueDate' => ['nullable', 'date'],
            'nextMonth' => ['nullable', 'date'],
            'discountDate' => ['nullable', 'date'],
            'default' => ['nullable', 'boolean'], // defaultTerms
            'active' => ['nullable', 'boolean'], // activeFlag
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
            'duplicateFields' => [],
        ];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
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
