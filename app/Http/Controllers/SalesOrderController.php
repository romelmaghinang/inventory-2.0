<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Product;
use App\Models\qbClass;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\SalesOrderStatus;
use App\Models\State;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderController extends Controller
{
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest): JsonResponse
    {
        try {
            $data = [
                'billToCountry' => Country::where('name', $storeSalesOrderRequest->billToCountry)->firstOrFail(),
                'billToState' => State::where('name', $storeSalesOrderRequest->billToState)->firstOrFail(),
                'shipToCountry' => Country::where('name', $storeSalesOrderRequest->shipToCountry)->firstOrFail(),
                'shipToState' => State::where('name', $storeSalesOrderRequest->shipToState)->firstOrFail(),
                'qbclass' => qbClass::where('name', $storeSalesOrderRequest->quickBookClassName)->firstOrFail(),
                'status' => SalesOrderStatus::where('id', $storeSalesOrderRequest->status)->firstOrFail(),
                'currency' => Currency::where('name', $storeSalesOrderRequest->currencyName)->firstOrFail(),
                'carrier' => Carrier::where('name', $storeSalesOrderRequest->carrierName)->firstOrFail(),
                'carrierService' => CarrierService::where('name', $storeSalesOrderRequest->carrierService)->firstOrFail(),
                'taxRate' => TaxRate::where('name', $storeSalesOrderRequest->taxRateName)->firstOrFail(),
            ];
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $customer = Customer::firstOrCreate(['name' => $storeSalesOrderRequest->customerName]);

        $lastNum = optional(SalesOrder::orderBy('id', 'desc')->first())->num;
        $newNum = $lastNum ? (string)((int)$lastNum + 1) : '1001';

        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->only([
                'customerName',
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'orderDateScheduled',
                'poNum',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
                'shippingTerms',
                'priorityId',
                'paymentTerms',
                'fob',
                'note',
                'locationGroupName',
                'phone',
                'email',
                'url',
                'category',
                'customField',
                'currencyRate',
            ]) + [
                'billToCountryId' => $data['billToCountry']->id,
                'billToStateId' => $data['billToState']->id,
                'shipToCountryId' => $data['shipToCountry']->id,
                'shipToStateId' => $data['shipToState']->id,
                'taxRateId' => $data['taxRate']->id,
                'statusId' => $data['status']->id,
                'currencyId' => $data['currency']->id,
                'customerId' => $customer->id,
                'carrierId' => $data['carrier']->id,
                'carrierServiceId' => $data['carrierService']->id,
                'residentialFlag' => $storeSalesOrderRequest->shipToResidential,
                'qbClassId' => $data['qbclass']->id,
                'num' =>  $storeSalesOrderRequest->soNum ?? $newNum,
            ]
        );

        $salesOrderItems = [];

        foreach ($storeSalesOrderRequest->validated()['items'] as $item) {
            $product = Product::where('num', $item['productNumber'])->firstOrFail();
            $qbClass = qbClass::firstOrCreate(['name' => $item['itemQuickBooksClassName']]);

            $transformedItem = [
                'note' => $item['note'],
                'typeId' => $item['soItemTypeId'],
                'oumId' => $item['uom'],
                'productId' => $product->id,
                'productNum' => $item['productNumber'],
                'showItemFlag' => $item['showItem'],
                'taxRateCode' => $item['taxCode'],
                'taxableFlag' => $item['taxable'],
                'customerPartNum' => $item['customerPartNumber'],
                'description' => $item['productDescription'],
                'qtyOrdered' => $item['productQuantity'],
                'unitPrice' => $item['productPrice'],
                'dateScheduledFulfillment' => $item['itemDateScheduled'],
                'revLevel' => $item['revisionLevel'],
                'customFieldItem' => $item['cfi'],
                'soId' => $salesOrder->id,
                'qbClassId' => $qbClass->id,
                'statusId' => $data['status']->id,
            ];

            $salesOrderItems[] = SalesOrderItems::create($transformedItem);
        }

        return response()->json(
            [
                'message' => 'Sales Order created successfully',
                'salesOrderData' => $salesOrder,
                'salesOrderItemData' => $salesOrderItems,
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
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest, SalesOrder $salesOrder): JsonResponse
    {
        try {
            $data = [
                'billToCountry' => Country::where('name', $updateSalesOrderRequest->billToCountry)->firstOrFail(),
                'billToState' => State::where('name', $updateSalesOrderRequest->billToState)->firstOrFail(),
                'shipToCountry' => Country::where('name', $updateSalesOrderRequest->shipToCountry)->firstOrFail(),
                'shipToState' => State::where('name', $updateSalesOrderRequest->shipToState)->firstOrFail(),
                'qbclass' => qbClass::where('name', $updateSalesOrderRequest->quickBookClassName)->firstOrFail(),
                'status' => SalesOrderStatus::where('id', $updateSalesOrderRequest->status)->firstOrFail(),
                'currency' => Currency::where('name', $updateSalesOrderRequest->currencyName)->firstOrFail(),
                'carrier' => Carrier::where('name', $updateSalesOrderRequest->carrierName)->firstOrFail(),
                'carrierService' => CarrierService::where('name', $updateSalesOrderRequest->carrierService)->firstOrFail(),
                'taxRate' => TaxRate::where('name', $updateSalesOrderRequest->taxRateName)->firstOrFail(),
            ];
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $customer = Customer::firstOrCreate(['name' => $updateSalesOrderRequest->customerName]);

        $salesOrder->update(
            $updateSalesOrderRequest->only([
                'customerName',
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'orderDateScheduled',
                'poNum',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
                'shippingTerms',
                'priorityId',
                'paymentTerms',
                'fob',
                'note',
                'locationGroupName',
                'phone',
                'email',
                'url',
                'category',
                'customField',
                'currencyRate',
            ]) + [
                'billToCountryId' => $data['billToCountry']->id,
                'billToStateId' => $data['billToState']->id,
                'shipToCountryId' => $data['shipToCountry']->id,
                'shipToStateId' => $data['shipToState']->id,
                'taxRateId' => $data['taxRate']->id,
                'statusId' => $data['status']->id,
                'currencyId' => $data['currency']->id,
                'customerId' => $customer->id,
                'carrierId' => $data['carrier']->id,
                'carrierServiceId' => $data['carrierService']->id,
                'residentialFlag' => $updateSalesOrderRequest->shipToResidential,
                'qbClassId' => $data['qbclass']->id,
                'num' => $updateSalesOrderRequest->soNum ?? $salesOrder->num,
            ]
        );

        // Remove existing items
        $salesOrder->items()->delete();

        $salesOrderItems = [];

        foreach ($updateSalesOrderRequest->validated()['items'] as $item) {
            $product = Product::where('num', $item['productNumber'])->firstOrFail();
            $qbClass = qbClass::firstOrCreate(['name' => $item['itemQuickBooksClassName']]);

            $transformedItem = [
                'note' => $item['note'],
                'typeId' => $item['soItemTypeId'],
                'oumId' => $item['uom'],
                'productId' => $product->id,
                'productNum' => $item['productNumber'],
                'showItemFlag' => $item['showItem'],
                'taxRateCode' => $item['taxCode'],
                'taxableFlag' => $item['taxable'],
                'customerPartNum' => $item['customerPartNumber'],
                'description' => $item['productDescription'],
                'qtyOrdered' => $item['productQuantity'],
                'unitPrice' => $item['productPrice'],
                'dateScheduledFulfillment' => $item['itemDateScheduled'],
                'revLevel' => $item['revisionLevel'],
                'customFieldItem' => $item['cfi'],
                'soId' => $salesOrder->id,
                'qbClassId' => $qbClass->id,
                'statusId' => $data['status']->id,
            ];

            $salesOrderItems[] = SalesOrderItems::create($transformedItem);
        }

        return response()->json(
            [
                'message' => 'Sales Order updated successfully',
                'salesOrderData' => $salesOrder,
                'salesOrderItemData' => $salesOrderItems,
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
