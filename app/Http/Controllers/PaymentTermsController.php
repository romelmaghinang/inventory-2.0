<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerms;
use App\Models\PaymentTermsType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentTermsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $paymentTermsType = PaymentTermsType::firstOrCreate(['name' => $request->name]);

        $paymentTerms = PaymentTerms::firstOrCreate(
            [
                'activeFlag' => $request->activeFlag,
                'defaultTerm' => $request->defaultTerm,
                'discount' => $request->discount,
                'discountDays' => $request->discountDays,
                'name' => $request->name,
                'netDays' => $request->netDays,
                'nextMonth' => $request->nextMonth,
                'readOnly' => $request->readOnly,
                'typeId' => $paymentTermsType->id
            ]
        );

        return response()->json($paymentTerms);
    }
}
