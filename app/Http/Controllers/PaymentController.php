<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class PaymentController extends Controller
{
    public function getPaymentTerm(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $defaultTerm = $request->input('defaultTerm', 1);
        $readOnly = $request->input('readOnly', 0);
        $typeName = $request->input('typeName');

        $paymentTerms = new Payment();
        $paymentTermData = $paymentTerms->getPaymentTermsId($name, $defaultTerm, $readOnly, $typeName);

        return response()->json($paymentTermData);
    }
}
