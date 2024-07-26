<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesOrderItem\StoreSalesOrderItemRequest;
use App\Http\Requests\SalesOrderItem\UpdateSalesOrderItemRequest;
use App\Models\SalesOrderItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderItemController extends Controller
{
    public function store(StoreSalesOrderItemRequest $storeSalesOrderItemRequest): JsonResponse
    {
        $salesOrderItems = [];

        foreach ($storeSalesOrderItemRequest->validated()['items'] as $item) {
            $item['typeId'] = $item['soItemTypeId'];
            $item['oumId'] = $item['uom'];
            $item['productNum'] = $item['productNumber'];
            $item['showItemFlag'] = $item['showItem'];
            $item['taxRateCode'] = $item['taxCode'];
            $item['taxableFlag'] = $item['taxable'];
            $item['customerPartNum'] = $item['customerPartNumber'];
            $item['description'] = $item['productDescription'];
            $item['qtyOrdered'] = $item['productQuantity'];
            $item['unitPrice'] = $item['productPrice'];
            $item['dateScheduledFulfillment'] = $item['itemScheduledFulfillment'];
            $item['revLevel'] = $item['revisionLevel'];

            $qbClass = qbClass::firstOrCreate(
                ['name' => $item['itemQuickBooksClassName']]
            );
            $item['qbClassId'] = $qbClass->id;

            $salesOrderItems[] = SalesOrderItems::create($item);
        }

        return response()->json(
            [
                'data' => $salesOrderItems,
                'message' => 'Sales Order Item Created Successfully!'
            ],
            Response::HTTP_CREATED
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(SalesOrderItems $salesOrderItems): JsonResponse
    {
        return response()->json($salesOrderItems, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesOrderItemRequest $updateSalesOrderItemRequest, SalesOrderItems $salesOrderItems): JsonResponse
    {
        $salesOrderItems->update(
            $updateSalesOrderItemRequest->validated() +
                [
                    'typeId' => $updateSalesOrderItemRequest->soItemTypeId,
                    'oumId' => $updateSalesOrderItemRequest->uom,
                    'productNum' => $updateSalesOrderItemRequest->productNumber,
                    'showItemFlag' => $updateSalesOrderItemRequest->showItem,
                    'taxRateCode' => $updateSalesOrderItemRequest->taxCode,
                    'taxableFlag' => $updateSalesOrderItemRequest->taxable,
                    'customerPartNum' => $updateSalesOrderItemRequest->customerPartNumber,
                    'description' => $updateSalesOrderItemRequest->productDescription,
                    'qtyOrdered' => $updateSalesOrderItemRequest->productQuantity,
                    'unitPrice' => $updateSalesOrderItemRequest->productPrice,
                    'dateScheduledFulfillment' => $updateSalesOrderItemRequest->itemScheduledFulfillment,
                    'revLevel' => $updateSalesOrderItemRequest->revisionLevel,
                ]
        );

        return response()->json(
            [
                'data' => $salesOrderItems,
                'message' => 'Sales Order Item Updated Successfully!'
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrderItems $salesOrderItems): JsonResponse
    {
        $salesOrderItems->delete();

        return response()->json(
            [
                'message' => 'Sales Order Item Deleted SuccessfulLy!'
            ],
            Response::HTTP_OK
        );
    }
}
