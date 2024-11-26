<?php

namespace App\Http\Requests\Pick;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Part;
use App\Models\Tag;
use App\Models\Serial;
use App\Models\SerialNum;

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
            'pickNum' => ['required', 'numeric', 'exists:so,num', 'unique:pick,pickNum'], // Added unique rule for duplicate check
            'locationName' => ['required', 'string', 'max:255', 'exists:location,name'],
            'partNum' => ['required', 'string', 'max:255', 'exists:part,num'],
            'partTrackingType' => ['required', 'string', 'exists:parttracking,name'],
            'trackingInfo' => [
                'required_if:partTrackingType,Serial Number',
                'array',
                'bail',
                function ($attribute, $value, $fail) {
                    if ($this->input('partTrackingType') === 'Serial Number') {
                        $partNum = $this->input('partNum');

                        $part = Part::where('num', $partNum)->first();
                        if (!$part) {
                            $fail("Invalid part number.");
                            return;
                        }

                        $tags = Tag::where('partId', $part->id)->pluck('id');
                        if ($tags->isEmpty()) {
                            $fail("No tags found for the specified part.");
                            return;
                        }

                        $serials = Serial::whereIn('tagId', $tags)->pluck('id');
                        if ($serials->isEmpty()) {
                            $fail("No serials found for the specified part.");
                            return;
                        }

                        $serialNums = SerialNum::whereIn('serialId', $serials)->pluck('serialNum')->toArray();
                        $invalidSerials = array_diff($value, $serialNums);
                        if (!empty($invalidSerials)) {
                            $fail("Invalid tracking serial numbers: " . implode(", ", $invalidSerials));
                        }

                        if (count($value) !== count($serialNums)) {
                            $fail("The number of tracking serials does not match the required count.");
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
