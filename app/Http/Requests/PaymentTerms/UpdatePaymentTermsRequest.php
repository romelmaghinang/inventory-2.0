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
            'termsName' => ['required', 'string', 'unique:paymentterms,name,' . $this->route('payment_term')], 
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
        $response = new JsonResponse([
            'success' => false,
            'message' => 'Validation errors occurred.',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
