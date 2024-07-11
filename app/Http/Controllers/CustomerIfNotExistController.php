<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerIfNotExistController extends Controller
{
    /**
     * Handle the incoming request. 
     */
    public function __invoke(Request $request): JsonResponse
    {
        $customer = Customer::firstOrCreate(
            ['name' => $request->customerName],
            [
                'accountId' => $request->accountId,
                'statusId' => $request->status,
                'taxExempt' => $request->taxExempt,
                'defaultSalesmanId' => $request->defaultSalesmanId,
                'toBeEmailed' => $request->toBeEmailed,
                'toBePrinted' => $request->toBePrinted,
            ]
        );

        return response()->json($customer);
    }
}
