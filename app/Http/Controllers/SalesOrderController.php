<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Models\AccountType;
use App\Models\SalesOrder;
use App\Models\SalesOrderItemType;
use App\Models\SalesOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SalesOrderController extends Controller
{
    /**true;true;
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest): JsonResponse
    {
        // ACCOUNT TYPE
        $accountType = AccountType::firstOrCreate(['name' => $storeSalesOrderRequest->accountTypeName]);

        // LOCATION GROUP
        $locationGroupData =
            [
                'locationGroupName' => $storeSalesOrderRequest->locationGroupName,
                'activeFlag' => $storeSalesOrderRequest->activeFlag,
                'countedAsAvailable' => $storeSalesOrderRequest->countedAsAvailable,
                'defaultFlag' => $storeSalesOrderRequest->defaultFlag,
                'locationName' => $storeSalesOrderRequest->locationName,
                'pickable' => $storeSalesOrderRequest->pickable,
                'receivable' => $storeSalesOrderRequest->receivable,
                'sortOrder' => $storeSalesOrderRequest->sortOrder,
                'typeId' => $accountType->id,
            ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

        // ADDRESS
        $addressData =
            [
                'accountId' => $accountType->id,
                'billToName' => $storeSalesOrderRequest->billToName,
                'billToCity' => $storeSalesOrderRequest->billToCity,
                'billToCountryName' => $storeSalesOrderRequest->billToCountryName,
                'defaultFlag' => $storeSalesOrderRequest->defaultFlag,
                'locationGroupId' => $locationGroupResponse->locationGroupId,
                'billToAddress' => $storeSalesOrderRequest->billToAddress,
                'billToStateName' => $storeSalesOrderRequest->billToStateName,
                'billToZip' => $storeSalesOrderRequest->billToZip,
                // SHIP
                'shipToName' => $storeSalesOrderRequest->shipToName,
                'shipToCity' => $storeSalesOrderRequest->shipToCity,
                'shipToCountryName' => $storeSalesOrderRequest->shipToCountryName,
                'shipToAddress' => $storeSalesOrderRequest->shipToAddress,
                'shipToStateName' => $storeSalesOrderRequest->shipToStateName,
                'shipToZip' => $storeSalesOrderRequest->shipToZip,
            ];

        $addressRequest = new Request($addressData);

        $addressController = new AddressController();
        $addressResponse = $addressController($addressRequest)->getData();

        // CUSTOMER
        $customerData =
            [
                'accountId' => $accountType->id,
                'customerName' => $storeSalesOrderRequest->customerName,
                'status' => $storeSalesOrderRequest->status,
                'defaultSalesmanId' => $storeSalesOrderRequest->salesmanId,
                'taxExempt' => $storeSalesOrderRequest->taxExempt,
                'toBeEmailed' => $storeSalesOrderRequest->toBeEmailed,
                'toBePrinted' => $storeSalesOrderRequest->toBePrinted,
            ];

        $customerRequest = new Request($customerData);
        $customerController = new CustomerIfNotExistController();
        $customerResponse = $customerController($customerRequest)->getData();

        // PRODUCT
        $productData =
            [
                'defaultSoItemType' => $storeSalesOrderRequest->soItemTypeName,
                'details' => $storeSalesOrderRequest->productDetails,
            ];

        $productRequest = new Request($productData);
        $productController = new ProductIfNotExistController();
        $productResponse = $productController($productRequest)->getData();


        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->except(
                [
                    'billToCountryName',
                    'billToStateName',
                    'currencyName',
                    'locationGroupName',
                    'paymentTermsName',
                    'priorityName',
                    'quickBookName',
                    'shipToCountryName',
                    'shipToStateName',
                    'taxRateName',
                ]
            ) +
            [
                'customerId' => $customerResponse->id,
                'billToCountryId' => $addressResponse->billToCountryId,
                'billToStateId' => $addressResponse->billToStateId,
                // currencyId
                'locationGroupId' => $locationGroupResponse->id,
                // paymentTermsId
                // priorityId
                // qbClassId
                'shipToCountryId' => $addressResponse->shipToCountryId,
                'shipToStateId' => $addressResponse->shipToStateId,
                // taxRateId
            ]
        );

        // SALES ORDER ITEM TYPE
        $salesOrderItemType = SalesOrderItemType::firstOrCreate(['name' => $storeSalesOrderRequest->salesOrderItemTypeName]);
        // SALES ORDER STATUS
        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $storeSalesOrderRequest->salesOrderStatus]);

        // SALES ORDER ITEMS
        $salesOrderItemData =
            [
                'productId' => $productResponse->id,
                'note' => $storeSalesOrderRequest->note,
                'showItemFlag' => $storeSalesOrderRequest->defaultFlag,
                'soLineItem' => $storeSalesOrderRequest->salesOrderLineItem,
                'soId' => $salesOrder->id,
                'statusId' => $salesOrderStatus->id,
                'typeId' => $salesOrderItemType->id,
            ];

        $salesOrderItemRequest = new Request($salesOrderItemData);
        $salesOrderItemController = new SalesOrderItemController();
        $salesOrderItemResponse = $salesOrderItemController($salesOrderItemRequest)->getData();

        return response()->json([
            'data' => $salesOrder,
            'products' => $productResponse,
            'salesOrderItem' => $salesOrderItemResponse,
            'message' => 'Success',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder): JsonResponse
    {
        return response()->json($salesOrder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest, $id): JsonResponse
    {
        $salesOrder = SalesOrder::findOrFail($id);

        // ACCOUNT TYPE
        $accountType = AccountType::firstOrCreate(['name' => $updateSalesOrderRequest->accountTypeName]);

        // LOCATION GROUP
        $locationGroupData = [
            'locationGroupName' => $updateSalesOrderRequest->locationGroupName,
            'activeFlag' => $updateSalesOrderRequest->activeFlag,
            'countedAsAvailable' => $updateSalesOrderRequest->countedAsAvailable,
            'defaultFlag' => $updateSalesOrderRequest->defaultFlag,
            'locationName' => $updateSalesOrderRequest->locationName,
            'pickable' => $updateSalesOrderRequest->pickable,
            'receivable' => $updateSalesOrderRequest->receivable,
            'sortOrder' => $updateSalesOrderRequest->sortOrder,
            'typeId' => $accountType->id,
        ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

        // ADDRESS
        $addressData = [
            'accountId' => $accountType->id,
            'billToName' => $updateSalesOrderRequest->billToName,
            'billToCity' => $updateSalesOrderRequest->billToCity,
            'billToCountryName' => $updateSalesOrderRequest->billToCountryName,
            'defaultFlag' => $updateSalesOrderRequest->defaultFlag,
            'locationGroupId' => $locationGroupResponse->locationGroupId,
            'billToAddress' => $updateSalesOrderRequest->billToAddress,
            'billToStateName' => $updateSalesOrderRequest->billToStateName,
            'billToZip' => $updateSalesOrderRequest->billToZip,
            // SHIP
            'shipToName' => $updateSalesOrderRequest->shipToName,
            'shipToCity' => $updateSalesOrderRequest->shipToCity,
            'shipToCountryName' => $updateSalesOrderRequest->shipToCountryName,
            'shipToAddress' => $updateSalesOrderRequest->shipToAddress,
            'shipToStateName' => $updateSalesOrderRequest->shipToStateName,
            'shipToZip' => $updateSalesOrderRequest->shipToZip,
        ];

        $addressRequest = new Request($addressData);
        $addressController = new AddressController();
        $addressResponse = $addressController($addressRequest)->getData();

        // CUSTOMER
        $customerData = [
            'accountId' => $accountType->id,
            'customerName' => $updateSalesOrderRequest->customerName,
            'status' => $updateSalesOrderRequest->status,
            'defaultSalesmanId' => $updateSalesOrderRequest->salesmanId,
            'taxExempt' => $updateSalesOrderRequest->taxExempt,
            'toBeEmailed' => $updateSalesOrderRequest->toBeEmailed,
            'toBePrinted' => $updateSalesOrderRequest->toBePrinted,
        ];

        $customerRequest = new Request($customerData);
        $customerController = new CustomerIfNotExistController();
        $customerResponse = $customerController($customerRequest)->getData();

        // PRODUCT
        $productData = [
            'defaultSoItemType' => $updateSalesOrderRequest->soItemTypeName,
            'details' => $updateSalesOrderRequest->productDetails,
        ];

        $productRequest = new Request($productData);
        $productController = new ProductIfNotExistController();
        $productResponse = $productController($productRequest)->getData();

        $salesOrder->update($updateSalesOrderRequest->except([
                'billToCountryName',
                'billToStateName',
                'currencyName',
                'locationGroupName',
                'paymentTermsName',
                'priorityName',
                'quickBookName',
                'shipToCountryName',
                'shipToStateName',
                'taxRateName',
            ]) + [
                'customerId' => $customerResponse->id,
                'billToCountryId' => $addressResponse->billToCountryId,
                'billToStateId' => $addressResponse->billToStateId,
                'locationGroupId' => $locationGroupResponse->id,
                'shipToCountryId' => $addressResponse->shipToCountryId,
                'shipToStateId' => $addressResponse->shipToStateId,
            ]);

        // SALES ORDER ITEM TYPE
        $salesOrderItemType = SalesOrderItemType::firstOrCreate(['name' => $updateSalesOrderRequest->salesOrderItemTypeName]);
        // SALES ORDER STATUS
        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $updateSalesOrderRequest->salesOrderStatus]);

        // SALES ORDER ITEMS
        $salesOrderItemData = [
            'productId' => $productResponse->id,
            'note' => $updateSalesOrderRequest->note,
            'showItemFlag' => $updateSalesOrderRequest->defaultFlag,
            'soLineItem' => $updateSalesOrderRequest->salesOrderLineItem,
            'soId' => $salesOrder->id,
            'statusId' => $salesOrderStatus->id,
            'typeId' => $salesOrderItemType->id,
        ];

        $salesOrderItemRequest = new Request($salesOrderItemData);
        $salesOrderItemController = new SalesOrderItemController();
        $salesOrderItemResponse = $salesOrderItemController($salesOrderItemRequest)->getData();

        return response()->json([
            'data' => $salesOrder,
            'products' => $productResponse,
            'salesOrderItem' => $salesOrderItemResponse,
            'message' => 'Sales Order updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder): JsonResponse
    {
        $salesOrder->delete();

        return response()->json(['message' => 'Sales order deleted successfully']);
    }
}
