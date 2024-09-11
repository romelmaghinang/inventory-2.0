<?php

namespace App\Rules;

use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTrackingType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Symfony\Component\HttpFoundation\Response;

class PartTrackingTypeRule implements ValidationRule
{
    protected $partNum;

    public function __construct(string $partNum)
    {
        $this->partNum = $partNum;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $part = Part::where('num', $this->partNum)->firstOrFail();

        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();

        $partTrackingType = PartTrackingType::where('name', $value)->firstOrFail();

        if ($partToTracking->partTracking->typeId !== $partTrackingType->id) {
            $fail('The Part Tracking Type is Incorrect');
        }
    }
}
