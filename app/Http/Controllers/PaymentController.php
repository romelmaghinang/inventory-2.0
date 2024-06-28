<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPaymentTermsId(Request $request)
    {
        $paymentTermsId = $request->input('name');
        $payment = new Payment();
        return $payment->getPaymentTermsIdByName($paymentTermsId);
    }
}
