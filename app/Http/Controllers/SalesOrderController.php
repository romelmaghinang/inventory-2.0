<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Http\Requests\SalesOrderItem\StoreSalesOrderItemRequest;
use App\Http\Requests\SalesOrderItem\UpdateSalesOrderItemRequest;
use App\Models\Account;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Country;
use App\Models\Customer;
use App\Models\qbClass;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\SalesOrderStatus;
use App\Models\State;
use App\Models\TaxRate;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderController extends Controller
{
        public function store(StoreSalesOrderRequest $storeSalesOrderRequest, StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $billToCountry = Country::firstOrCreate(['name' => $storeSalesOrderRequest->billToCountry]);
        $billToState = State::firstOrCreate(['name' => $storeSalesOrderRequest->billToState]);
        $shipToCountry = Country::firstOrCreate(['name' => $storeSalesOrderRequest->shipToCountry]);
        $shipToState = State::firstOrCreate(['name' => $storeSalesOrderRequest->shipToState]);
        $qbclass = qbClass::firstOrCreate(['name' => $storeSalesOrderRequest->quickBookClassName]);
        $status = SalesOrderStatus::firstOrCreate(['name' => $storeSalesOrderRequest->status]);
        $carrier = Carrier::where('name', $storeSalesOrderRequest->carrierName)->first();
        // $carrierService = CarrierService::where('name', $storeSalesOrderRequest->carrierService)->first();
        $taxRate = TaxRate::firstOrCreate(['name' => $storeSalesOrderRequest->taxRateName]);
        $customerId = null;

        //customer
        if ($storeCustomerRequest->customerName !== null) {
            $account = Account::create(['typeId' => $storeCustomerRequest->accountTypeId]);
            $customer = Customer::firstOrNew(
                ['name' => $storeCustomerRequest->customerName]
            );

            //check if the customer is newly created
            if (!$customer->exists) {
                $customer->fill($storeCustomerRequest->except([
                    'accountTypeId',
                    'city',
                    'countryId',
                    'locationGroupId',
                    'addressName',
                    'pipelineContactNum',
                    'stateId',
                    'address',
                    'typeId',
                    'zip'
                ]) + [
                    'statusId' => $storeSalesOrderRequest->status,
                    'accountId' => $account->id,
                ]);
                $customer->save();

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
                    ]) + [
                        'accountId' => $account->id,
                    ]
                );
            }
            $customerId = $customer->id;
        }

        // SoNum
        $lastNum = optional(SalesOrder::orderBy('id', 'desc')->first())->num;
        $newNum = $lastNum ? (string)((int)$lastNum + 1) : '1001';

        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->except('items') +
                [
                    'billToCountryId' => $billToCountry->id,
                    'billToStateId' => $billToState->id,
                    'shipToCountryId' => $shipToCountry->id,
                    'shipToStateId' => $shipToState->id,
                    'taxRateId' => $taxRate->id,
                    // 'taxRate' => $taxRate->rate,
                    'statusId' => $storeSalesOrderRequest->status,
                    'customerId' => $customerId,
                    'carrierId' => $carrier->id,
                    // 'carrierServiceId' => $carrierService->id,
                    'residentialFlag' => $storeSalesOrderRequest->shipToResidential,
                    'qbClassId' => $qbclass->id,
                    'num' =>  $storeSalesOrderRequest->soNum ?? $newNum,
                ]
        );

        return response()->json(
            [
                'salesOrder' => $salesOrder,
                'customer' => $customer ?? null,
                'address' => $address ?? null,
                'message' => 'Sales Order Created Successfully!',
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder): JsonResponse
    {
        return response()->json($salesOrder, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest, UpdateCustomerRequest $updateCustomerRequest, SalesOrder $salesOrder): JsonResponse
    {
        $billToCountry = Country::firstOrCreate(['name' => $updateSalesOrderRequest->billToCountry]);
        $billToState = State::firstOrCreate(['name' => $updateSalesOrderRequest->billToState]);
        $shipToCountry = Country::firstOrCreate(['name' => $updateSalesOrderRequest->shipToCountry]);
        $shipToState = State::firstOrCreate(['name' => $updateSalesOrderRequest->shipToState]);
        $qbclass = qbClass::firstOrCreate(['name' => $updateSalesOrderRequest->quickBookClassName]);
        $status = SalesOrderStatus::where(['name' => $updateSalesOrderRequest->status]);
        $carrier = Carrier::where('name', $updateSalesOrderRequest->carrierName)->first();
        $taxRate = TaxRate::firstOrCreate(['name' => $updateSalesOrderRequest->taxRateName]);

        //customer
        if ($updateCustomerRequest->customerName !== null) {
            $customer = Customer::findOrFail($salesOrder->customerId);

            $customer->update($updateCustomerRequest->except([
                'accountTypeId',
                'city',
                'countryId',
                'locationGroupId',
                'addressName',
                'pipelineContactNum',
                'stateId',
                'address',
                'typeId',
                'zip'
            ]) + [
                'statusId' => $updateSalesOrderRequest->status,
            ]);

            // Update the associated address
            $address = Address::where('accountId', $customer->accountId)->first();
            if ($address) {
                $address->update(
                    $updateCustomerRequest->only([
                        'name',
                        'countryId',
                        'locationGroupId',
                        'addressName',
                        'pipelineContactNum',
                        'stateId',
                        'address',
                        'typeId',
                        'zip'
                    ])
                );
            }
        }

        $salesOrder->update(
            $updateSalesOrderRequest->except('items') +
                [
                    'billToCountryId' => $billToCountry->id,
                    'billToStateId' => $billToState->id,
                    'shipToCountryId' => $shipToCountry->id,
                    'shipToStateId' => $shipToState->id,
                    'taxRateId' => $taxRate->id,
                    'statusId' => $updateSalesOrderRequest->status,
                    'carrierId' => $carrier->id,
                    'residentialFlag' => $updateSalesOrderRequest->shipToResidential,
                    'qbClassId' => $qbclass->id,
                ]
        );

        return response()->json(
            [
                'salesOrder' => $salesOrder,
                'customer' => $customer ?? null,
                'address' => $address ?? null,
                'message' => 'Sales Order Updated Successfully!',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder): JsonResponse
    {
        $salesOrder->delete();

        return response()->json(
            [
                'message' => 'Sales Order Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
