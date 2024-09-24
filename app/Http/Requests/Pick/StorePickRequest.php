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
            'pickNum' => ['required', 'numeric', 'exists:so,num'],
            'locationName' => ['required', 'string', 'max:255', 'exists:location,name'],
            'partNum' => ['required', 'string', 'max:255', 'exists:part,num'],
            'partTrackingType' => ['required', 'string', 'exists:parttracking,name'],
            'trackingInfo' => ['required', 'array', 'bail', function ($attribute, $value, $fail) {
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
            }],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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