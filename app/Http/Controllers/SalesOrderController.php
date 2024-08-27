<?php

namespace App\Http\Controllers;

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
use App\Models\ShipTerms;
use App\Models\State;
use App\Models\TaxRate;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderController extends Controller
{
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest): JsonResponse
    {
        $billToCountry = Country::where('name', $storeSalesOrderRequest->billToCountry)->firstOrFail();
        $billToState = State::where('name', $storeSalesOrderRequest->billToState)->firstOrFail();
        $shipToCountry = Country::where('name', $storeSalesOrderRequest->shipToCountry)->firstOrFail();
        $shipToState = State::where('name', $storeSalesOrderRequest->shipToState)->firstOrFail();
        $qbclass = qbClass::where('name', $storeSalesOrderRequest->quickBookClassName)->firstOrFail();
        $currency = Currency::where('name', $storeSalesOrderRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $storeSalesOrderRequest->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $storeSalesOrderRequest->carrierService)->firstOrFail();
        $taxRate = TaxRate::where('name', $storeSalesOrderRequest->taxRateName)->firstOrFail();
        $shipterms = ShipTerms::where('name', $storeSalesOrderRequest->shippingTerms)->firstOrFail();

        $customer = Customer::firstOrCreate(['name' => $storeSalesOrderRequest->customerName]);

        $newNum = (string)((optional(SalesOrder::latest('id')->first())->num ?? 1000) + 1);

        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->only([
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
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
                'activeFlag' => $storeSalesOrderRequest->flag,
                'shipTermsId' => $shipterms->id,
                'billToCountryId' => $billToCountry->id,
                'billToStateId' => $billToState->id,
                'shipToCountryId' => $shipToCountry->id,
                'shipToStateId' => $shipToState->id,
                'taxRateId' => $taxRate->id,
                'statusId' => $storeSalesOrderRequest->status,
                'currencyId' => $currency->id,
                'customerId' => $customer->id,
                'carrierId' => $carrier->id,
                'carrierServiceId' => $carrierService->id,
                'residentialFlag' => $storeSalesOrderRequest->shipToResidential,
                'qbClassId' => $qbclass->id,
                'num' =>  $storeSalesOrderRequest->soNum ?? $newNum,
            ]
        );

        $salesOrderItems = [];

        foreach ($storeSalesOrderRequest->validated()['items'] as $item) {
            $product = Product::where('num', $item['productNumber'])->firstOrFail();
            $qbClass = qbClass::where('name', $item['itemQuickBooksClassName'])->firstOrFail();

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
                'statusId' => $storeSalesOrderRequest->status,
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
        $billToCountry = Country::where('name', $updateSalesOrderRequest->billToCountry)->firstOrFail();
        $billToState = State::where('name', $updateSalesOrderRequest->billToState)->firstOrFail();
        $shipToCountry = Country::where('name', $updateSalesOrderRequest->shipToCountry)->firstOrFail();
        $shipToState = State::where('name', $updateSalesOrderRequest->shipToState)->firstOrFail();
        $qbclass = qbClass::where('name', $updateSalesOrderRequest->quickBookClassName)->firstOrFail();
        $currency = Currency::where('name', $updateSalesOrderRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $updateSalesOrderRequest->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $updateSalesOrderRequest->carrierService)->firstOrFail();
        $taxRate = TaxRate::where('name', $updateSalesOrderRequest->taxRateName)->firstOrFail();
        $shipterms = ShipTerms::where('name', $updateSalesOrderRequest->shippingTerms)->firstOrFail();

        $customer = Customer::firstOrCreate(['name' => $updateSalesOrderRequest->customerName]);

        // Update Sales Order
        $salesOrder->update(
            $updateSalesOrderRequest->only([
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
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
                'activeFlag' => $updateSalesOrderRequest->flag,
                'shipTermsId' => $shipterms->id,
                'billToCountryId' => $billToCountry->id,
                'billToStateId' => $billToState->id,
                'shipToCountryId' => $shipToCountry->id,
                'shipToStateId' => $shipToState->id,
                'taxRateId' => $taxRate->id,
                'statusId' => $updateSalesOrderRequest->status,
                'currencyId' => $currency->id,
                'customerId' => $customer->id,
                'carrierId' => $carrier->id,
                'carrierServiceId' => $carrierService->id,
                'residentialFlag' => $updateSalesOrderRequest->shipToResidential,
                'qbClassId' => $qbclass->id,
            ]
        );

        // Remove existing items
        $salesOrder->items()->delete();

        $salesOrderItems = [];

        // Add new items
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
                'statusId' => $updateSalesOrderRequest->status,
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
