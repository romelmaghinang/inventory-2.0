<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStatus;
use App\Models\PaymentTerms;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Find or create a customer based on the type.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $paymentTerms = PaymentTerms::firstOrCreate(['name' => $request->name]);

        $customerStatus = CustomerStatus::firstOrCreate(['name'=> $request->status]);

        $customerDetails = Customer::firstOrCreate([
            'payment_terms_id' => $paymentTerms->id,
            'name' => $request->name,
            'number' => $request->number,
            'taxExempt' => $request->taxExempt,
            'toBeEmailed' => $request->toBeEmailed,
            'toBePrinted' => $request->toBePrinted,
            'url' => $request->url,
            'customer_status_id' => $customerStatus->id,
        ]);

        return response()->json($customerDetails);
    }
}
