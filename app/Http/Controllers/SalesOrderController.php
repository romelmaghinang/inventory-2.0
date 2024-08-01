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
use App\Models\SalesOrderStatus;
use App\Models\State;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
                'status' => SalesOrderStatus::where('id', $storeSalesOrderRequest->status)->firstOrFail(), // Have Data
                'currency' => Currency::where('name', $storeSalesOrderRequest->currencyName)->firstOrFail(),
                'carrier' => Carrier::where('name', $storeSalesOrderRequest->carrierName)->firstOrFail(), // Have Data
                'carrierService' => CarrierService::where('name', $storeSalesOrderRequest->carrierService)->firstOrFail(), // Have Data
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
            // Check if the product exists
            try {
                $product = Product::where('num', $item['productNumber'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Product not found',
                    'message' => "Product with number {$item['productNumber']} does not exist."
                ], Response::HTTP_NOT_FOUND);
            }

            // Handle QuickBooks class
            $qbClass = qbClass::firstOrCreate(['name' => $item['itemQuickBooksClassName']]);

            // Map and transform item data
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

            // Create and store the sales order item
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
    public function update(UpdateSalesOrderRequest $request, SalesOrder $salesOrder): JsonResponse
    {
        try {
            // Fetch the associated entities based on the request data
            $data = [
                'billToCountry' => Country::where('name', $request->billToCountry)->firstOrFail(),
                'billToState' => State::where('name', $request->billToState)->firstOrFail(),
                'shipToCountry' => Country::where('name', $request->shipToCountry)->firstOrFail(),
                'shipToState' => State::where('name', $request->shipToState)->firstOrFail(),
                'qbclass' => qbClass::where('name', $request->quickBookClassName)->firstOrFail(),
                'status' => SalesOrderStatus::where('name', $request->status)->firstOrFail(),
                'currency' => Currency::where('name', $request->currencyName)->firstOrFail(),
                'carrier' => Carrier::where('name', $request->carrierName)->firstOrFail(),
                'carrierService' => CarrierService::where('name', $request->carrierService)->firstOrFail(),
                'taxRate' => TaxRate::where('name', $request->taxRateName)->firstOrFail(),
            ];

            // Update Customer information
            $customer = Customer::updateOrCreate(
                ['name' => $request->customerName],
                ['status' => $data['status']->id]
            );

            // Update Sales Order
            $salesOrder->update($request->except('items') + [
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
                'residentialFlag' => $request->shipToResidential,
                'qbClassId' => $data['qbclass']->id,
                'num' => $request->soNum ?? $salesOrder->num,
            ]);

            // Update Sales Order Items
            $salesOrderItems = [];

            foreach ($request->validated()['items'] as $item) {
                try {
                    $product = Product::where('num', $item['productNumber'])->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return response()->json([
                        'error' => 'Product not found',
                        'message' => "Product with number {$item['productNumber']} does not exist."
                    ], Response::HTTP_NOT_FOUND);
                }

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
                ];

                $salesOrderItems[] = SalesOrderItems::updateOrCreate(
                    ['soId' => $salesOrder->id, 'productId' => $product->id],
                    $transformedItem
                );
            }

            return response()->json(
                [
                    'message' => 'Sales Order updated successfully',
                    'salesOrderData' => $salesOrder,
                    'salesOrderItemData' => $salesOrderItems,
                ],
                Response::HTTP_OK
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
