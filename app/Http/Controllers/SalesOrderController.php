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
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest, StoreSalesOrderItemRequest $storeSalesOrderItemRequest, StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
 
        $billToCountry = Country::firstOrCreate(['name' => $storeSalesOrderRequest->billToCountry]);
        $billToState = State::firstOrCreate(['name' => $storeSalesOrderRequest->billToState]);
        $shipToCountry = Country::firstOrCreate(['name' => $storeSalesOrderRequest->shipToCountry]);
        $shipToState = State::firstOrCreate(['name' => $storeSalesOrderRequest->shipToState]);
        $qbclass = qbClass::firstOrCreate(['name' => $storeSalesOrderRequest->quickBookClassName]);
        $status = SalesOrderStatus::firstOrCreate(['name' => $storeSalesOrderRequest->status]);
        $carrier = Carrier::where('name', $storeSalesOrderRequest->carrierName)->first();
        $carrierService = CarrierService::where('name', $storeSalesOrderRequest->carrierService)->first();
        $taxRate = TaxRate::firstOrCreate(['name' => $storeSalesOrderRequest->taxRateName]);
        $account = Account::create(['typeId' => $storeCustomerRequest->accountTypeId]);

        $customer  = Customer::firstOrCreate(
            ['name' => $storeCustomerRequest->customerName],
            $storeCustomerRequest->except([
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
            ]) +
                [
                    'statusId' => $storeSalesOrderRequest->status,
                    'accountId' => $account->id,
                ]
        );

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
                    'accountId' => $account->id,
                ]
        );

        // SoNum
        $lastNum = optional(SalesOrder::orderBy('id', 'desc')->first())->num;
        $newNum = $lastNum ? (string)((int)$lastNum + 1) : '10001';

        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->except('items') +
                [
                    'billToCountryId' => $billToCountry->id,
                    'billToStateId' => $billToState->id,
                    'shipToCountryId' => $shipToCountry->id,
                    'shipToStateId' => $shipToState->id,
                    'taxRateId' => $taxRate->id,
                    'taxRate' => $taxRate->rate,
                    'statusId' => $storeSalesOrderRequest->status,
                    'customerId' => $customer->id,
                    'carrierId' => $carrier->id,
                    'carrierServiceId' => $carrierService->id,
                    'residentialFlag' => $storeSalesOrderRequest->shipToResidential,
                    'qbClassId' => $qbclass->id,
                    'num' => $newNum,
                ]
        );

        $salesOrderItems = [];

        foreach ($storeSalesOrderItemRequest->validated()['items'] as $item) {
            $item['soId'] = $salesOrder->id;
            $item['statusId'] = $status->id;
            $item['taxableFlag'] = $storeSalesOrderItemRequest->Flas;
            $salesOrderItems[] = SalesOrderItems::create($item);
        }

        return response()->json(
            [
                'salesOrder' => $salesOrder,
                'salesOrderItem' => $salesOrderItems,
                'customer' => $customer,
                'address' => $address,
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
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest, UpdateSalesOrderItemRequest $updateSalesOrderItemRequest, SalesOrder $salesOrder): JsonResponse
    {
        
        $billToCountry = Country::firstOrCreate(['name' => $updateSalesOrderRequest->billToCountry]);
        $billToState = State::firstOrCreate(['name' => $updateSalesOrderRequest->billToState]);
        $shipToCountry = Country::firstOrCreate(['name' => $updateSalesOrderRequest->shipToCountry]);
        $shipToState = State::firstOrCreate(['name' => $updateSalesOrderRequest->shipToState]);
        $qbclass = qbClass::firstOrCreate(['name' => $updateSalesOrderRequest->quickBookClassName]);
        $status = SalesOrderStatus::firstOrCreate(['name' => $updateSalesOrderRequest->status]);
        $carrier = Carrier::where('name', $updateSalesOrderRequest->carrierName)->first();
        $carrierService = CarrierService::where('name', $updateSalesOrderRequest->carrierService)->first();
        $taxRate = TaxRate::where('name', $updateSalesOrderRequest->taxRateName)->first();

        $salesOrder->update(
            $updateSalesOrderRequest->except('items') +
                [
                    'billToCountryId' => $billToCountry->id,
                    'billToStateId' => $billToState->id,
                    'shipToCountryId' => $shipToCountry->id,
                    'shipToStateId' => $shipToState->id,
                    'taxRateId' => $taxRate->id,
                    'taxRate' => $taxRate->rate,
                    'statusId' => $status->id,
                    'carrierId' => $carrier->id,
                    'carrierServiceId' => $carrierService->id,
                    'residentialFlag' => $updateSalesOrderRequest->shipToResidential,
                    'qbClassId' => $qbclass->id,
                ]
        );

        // Update or create sales order items
        $updatedSalesOrderItems = [];

        foreach ($updateSalesOrderItemRequest->validated()['items'] as $item) {
            $item['soId'] = $salesOrder->id;
            $item['statusId'] = $status->id;

            if (isset($item['id'])) {
                $salesOrderItem = SalesOrderItems::findOrFail($item['id']);
                $salesOrderItem->update($item);
                $updatedSalesOrderItems[] = $salesOrderItem;
            } else {
                $updatedSalesOrderItems[] = SalesOrderItems::create($item);
            }
        }

        // Delete items that are not in the update request
        $existingItemIds = array_column($updateSalesOrderItemRequest->validated()['items'], 'id');
        SalesOrderItems::where('soId', $salesOrder->id)
            ->whereNotIn('id', $existingItemIds)
            ->delete();

        return response()->json(
            [
                'salesOrder' => $salesOrder,
                'salesOrderItems' => $updatedSalesOrderItems,
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
