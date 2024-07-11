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

        // BILL TO ADDRESS
        $billToAddressData =
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
            ];

        $billToAddressRequest = new Request($billToAddressData);

        $billToAddressController = new BillToAddressController();
        $billToAddressResponse = $billToAddressController($billToAddressRequest)->getData();

        // SHIP TO ADDRESS
        $shipToAddressData =
            [
                'accountId' => $accountType->id,
                'shipToName' => $storeSalesOrderRequest->shipToName,
                'shipToCity' => $storeSalesOrderRequest->shipToCity,
                'shipToCountryName' => $storeSalesOrderRequest->shipToCountryName,
                'defaultFlag' => $storeSalesOrderRequest->defaultFlag,
                'locationGroupId' => $locationGroupResponse->id,
                'shipToAddress' => $storeSalesOrderRequest->shipToAddress,
                'shipToStateName' => $storeSalesOrderRequest->shipToStateName,
                'shipToZip' => $storeSalesOrderRequest->shipToZip,
            ];

        $shipToAddressRequest = new Request($shipToAddressData);
        $shipToAddressController = new ShipToAddressController();
        $shipToAddressResponse = $shipToAddressController($shipToAddressRequest)->getData();

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


        $salesOrder = SalesOrder::create($storeSalesOrderRequest->except(
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
            ]) +
                [
                    'customerId' => $customerResponse->id,
                    'billToCountryId' => $billToAddressResponse->countryId,
                    'billToStateId' => $billToAddressResponse->stateId,
                    // currencyId
                    'locationGroupId' => $locationGroupResponse->id,
                    // paymentTermsId
                    // priorityId
                    // qbClassId
                    'shipToCountryId' => $shipToAddressResponse->countryId,
                    'shipToStateId' => $shipToAddressResponse->stateId,
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
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest, SalesOrder $salesOrder): JsonResponse
    {
        $salesOrder->update($updateSalesOrderRequest->validated());

        return response()->json(
            [
                'data' => $salesOrder,
                'message' => 'Sales Updated Successfully'
            ]
        );
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
