<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $validateCustomer = Customer::create($storeCustomerRequest->validated());

        $customer = Customer::firstOrCreate(
            ['name' => $validateCustomer->customerName],
            [
                'accountId' => $validateCustomer->accountId,
                'statusId' => $validateCustomer->status,
                'taxExempt' => $validateCustomer->taxExempt,
                'defaultSalesmanId' => $validateCustomer->defaultSalesmanId,
                'toBeEmailed' => $validateCustomer->toBeEmailed,
                'toBePrinted' => $validateCustomer->toBePrinted,
            ]
        );

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $updateCustomerRequest, Customer $customer): JsonResponse
    {
        $customer->update($updateCustomerRequest->validated());

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json('succcess');
    }
}
