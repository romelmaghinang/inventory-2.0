<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStatus;
use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Find or create a customer based on the type.
     */
    public function getOrCreateCustomer(Request $request)
    {
        $name = $request->input('name');
        $number = $request->input('number');
        $taxExempt = $request->input('taxExempt');
        $toBeEmailed = $request->input('toBeEmailed');
        $toBePrinted = $request->input('toBePrinted');
        $url = $request->input('url');

        // Retrieve defaultPaymentTermsId from paymentterms table
        $paymentTermsName = $request->input('name');
        $paymentTerms = PaymentTerms::where('name', $paymentTermsName)->first();
        if (!$paymentTerms) {
            return response()->json(['message' => 'Payment Terms not found'], 404);
        }
        $defaultPaymentTermsId = $paymentTerms->id;

        // Retrieve statusId from customerstatus table
        $name = $request->input('name');
        $customerStatus = CustomerStatus::where('name', $name)->first();
        if (!$customerStatus) {
            return response()->json(['message' => 'Customer Status not found'], 404);
        }
        $statusId = $customerStatus->id;

        $customer = new Customer();
        $customerDetails = $customer->getOrCreateCustomer(
            $name,
            $defaultPaymentTermsId,
            $statusId,
            $number,
            $taxExempt,
            $toBeEmailed,
            $toBePrinted,
            $url
        );

        return response()->json($customerDetails);
    }
}
