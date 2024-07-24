<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Account;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function store(StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $account = Account::create(['typeId' => $storeCustomerRequest->accountTypeId]);

        $customer = Customer::create($storeCustomerRequest->except(
            [
                'accountTypeId',
                'name',
                'city',
                'countryId',
                'locationGroupId',
                'addressName',
                'pipelineContactNum',
                'stateId',
                'address',
                'typeId',
                'zip'
            ]
        ) +
            [
                'accountId' => $account->id,
                'name' => $storeCustomerRequest->customerName,
            ]);

        $address = Address::create(
            $storeCustomerRequest->only([
                'name',
                'countryId',
                'locationGroupId',
                'addressName',
                'pipelineContactNum',
                'stateId',
                'address',
                'typeId',
                'zip'
            ]) +
                [
                    'accountId' => $account->id
                ]
        );

        return response()->json(
            [
                'customer' => $customer,
                'address' => $address,
                'message' => 'Customer Created Successfully!',
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $updateCustomerRequest, Customer $customer): JsonResponse
    {
        $account = Account::findOrFail($customer->accountId);
        $address = Address::where('accountId', $account->id)->firstOrFail();

        $account->update(['typeId' => $updateCustomerRequest->accountTypeId]);

        $customer->update($updateCustomerRequest->except(
            [
                'accountTypeId',
                'name',
                'city',
                'countryId',
                'locationGroupId',
                'addressName',
                'pipelineContactNum',
                'stateId',
                'address',
                'typeId',
                'zip'
            ]
        ) +
            [
                'accountId' => $account->id,
                'name' => $updateCustomerRequest->customerName,
            ]);

        $address->update(
            [
                'accountId' => $account->id,
                'name' => $updateCustomerRequest->name,
                'countryId' => $updateCustomerRequest->countryId,
                'locationGroupId' => $updateCustomerRequest->locationGroupId,
                'addressName' => $updateCustomerRequest->addressName,
                'pipelineContactNum' => $updateCustomerRequest->pipelineContactNum,
                'stateId' => $updateCustomerRequest->stateId,
                'address' => $updateCustomerRequest->address,
                'typeId' => $updateCustomerRequest->typeId,
                'zip' => $updateCustomerRequest->zip,
            ]
        );

        return response()->json(
            [
                'customer' => $customer,
                'address' => $address,
                'message' => 'Customer Updated Successfully!',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(
            [
                'message' => 'Customer Deleted Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
