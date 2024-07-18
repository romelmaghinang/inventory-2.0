<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Customer;
use App\Models\Priority;
use App\Models\Product;
use App\Models\qbClass;
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

        // ACCOUNT
        $account = Account::firstOrCreate(['typeId' => $accountType->id]);

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
                'typeId' => $account->id,
            ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

        // ADDRESS
        $addressData =
            [
                'accountId' => $account->id,
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

        $carrierData = [
            'activeFlag' => $storeSalesOrderRequest->activeFlag,
            'carrierServiceName' => $storeSalesOrderRequest->carrierServiceName,
            'readOnly' => $storeSalesOrderRequest->readOnly,
            'scac' => $storeSalesOrderRequest->scac,
            'carrierDescription' => $storeSalesOrderRequest->carrierDescription,
            'carrierCode' => $storeSalesOrderRequest->carrierCode,
        ];

        $carrierRequest = new Request($carrierData);

        $carrierController = new CarrierController();
        $carrierResponse = $carrierController($carrierRequest)->getData();

        // CUSTOMER
        $customer = Customer::firstOrCreate(
            [
                'name' => $storeSalesOrderRequest->customerName,
            ],
            [
                'accountId' => $account->id,
                'statusId' => $storeSalesOrderRequest->status,
                'taxExempt' => $storeSalesOrderRequest->taxExempt,
                'defaultSalesmanId' => $storeSalesOrderRequest->salesmanId,
                'toBeEmailed' => $storeSalesOrderRequest->toBeEmailed,
                'toBePrinted' => $storeSalesOrderRequest->toBePrinted,
            ]
        );

        $currencyData =
            [
                'activeFlag' => $storeSalesOrderRequest->activeFlag,
                'code' => $storeSalesOrderRequest->currencyCode,
                'excludeFromUpdate' => $storeSalesOrderRequest->excludeFromUpdate,
                'homeCurrency' => $storeSalesOrderRequest->homeCurrency,
                'symbol' => $storeSalesOrderRequest->currencySymbol,
            ];

        $currencyRequest = new Request($currencyData);
        $currencyController = new CurrencyController();
        $currencyResponse = $currencyController($currencyRequest)->getData();

        // SALES ORDER ITEM TYPE
        $salesOrderItemType = SalesOrderItemType::firstOrCreate(['name' => $storeSalesOrderRequest->salesOrderItemTypeName]);

        $product = Product::firstOrCreate(
            [
                'defaultSoItemType' => $salesOrderItemType->id,
                'details' => $storeSalesOrderRequest->productDetails,
            ]
        );

        $taxRateData =
            [
                'name' => $storeSalesOrderRequest->taxRateName,
                'activeFlag' => $storeSalesOrderRequest->activeFlag,
                'code' => $storeSalesOrderRequest->taxRateCode,
                'defaultFlag' => $storeSalesOrderRequest->defaultFlag,
                'description' => $storeSalesOrderRequest->taxRateDescription,
                'orderTypeId' => $salesOrderItemType->id,
                'rate' => $storeSalesOrderRequest->taxRate,
                'taxAccountId' => $account->id,
            ];

        $taxRateRequest = new Request($taxRateData);
        $taxRateController = new TaxRateController();
        $taxRateResponse = $taxRateController($taxRateRequest)->getData();

        $paymentTermsData =
            [
                'activeFlag' => $storeSalesOrderRequest->activeFlag,
                'defaultTerm' => $storeSalesOrderRequest->defaultTerm,
                'name' => $storeSalesOrderRequest->paymentTermsName,
                'discount' => $storeSalesOrderRequest->discount,
                'discountDays' => $storeSalesOrderRequest->discountDays,
                'netDays' => $storeSalesOrderRequest->netDays,
                'nextMonth' => $storeSalesOrderRequest->nextMonth,
                'readOnly' => $storeSalesOrderRequest->readOnly,
            ];

        $paymentTermsRequest = new Request($paymentTermsData);

        $paymentTermsController = new PaymentTermsController();
        $paymentTermsResponse = $paymentTermsController($paymentTermsRequest)->getData();

        $priority = Priority::firstOrCreate(['name' => $storeSalesOrderRequest->priorityName]);

        $qbclass = qbClass::firstOrCreate(
            [
                'name' => $storeSalesOrderRequest->quickBookName,
                'activeFlag' => $storeSalesOrderRequest->activeFlag,
            ]
        );

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
                'customerId' => $customer->id,
                'billToCountryId' => $addressResponse->billToCountryId,
                'billToStateId' => $addressResponse->billToStateId,
                'carrierId' => $carrierResponse->carrierId,
                'carrierServiceId' => $carrierResponse->carrierServiceId,
                'currencyId' => $currencyResponse->id,
                'locationGroupId' => $locationGroupResponse->id,
                'paymentTermsId' => $paymentTermsResponse->id,
                'priorityId' => $priority->id,
                'qbClassId' => $qbclass->id,
                'shipToCountryId' => $addressResponse->shipToCountryId,
                'shipToStateId' => $addressResponse->shipToStateId,
                'taxRateId' => $taxRateResponse->id,
            ]
        );

        // SALES ORDER STATUS
        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $storeSalesOrderRequest->salesOrderStatus]);

        // SALES ORDER ITEMS
        $salesOrderItemData =
            [
                'productId' => $product->id,
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
            'products' => $product,
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
        // ACCOUNT TYPE
        $accountType = AccountType::firstOrCreate(['name' => $updateSalesOrderRequest->accountTypeName]);

        // ACCOUNT
        $account = Account::updateOrCreate(
            ['id' => $salesOrder->accountId],
            ['typeId' => $accountType->id]
        );

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
            'typeId' => $account->id,
        ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

        // ADDRESS
        $addressData = [
            'accountId' => $account->id,
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

        $carrierData = [
            'activeFlag' => $updateSalesOrderRequest->activeFlag,
            'carrierServiceName' => $updateSalesOrderRequest->carrierServiceName,
            'readOnly' => $updateSalesOrderRequest->readOnly,
            'scac' => $updateSalesOrderRequest->scac,
            'carrierDescription' => $updateSalesOrderRequest->carrierDescription,
            'carrierCode' => $updateSalesOrderRequest->carrierCode,
        ];

        $carrierRequest = new Request($carrierData);
        $carrierController = new CarrierController();
        $carrierResponse = $carrierController($carrierRequest)->getData();

        // CUSTOMER
        $customer = Customer::updateOrCreate(
            ['id' => $salesOrder->customerId],
            [
                'name' => $updateSalesOrderRequest->customerName,
                'accountId' => $account->id,
                'statusId' => $updateSalesOrderRequest->status,
                'taxExempt' => $updateSalesOrderRequest->taxExempt,
                'defaultSalesmanId' => $updateSalesOrderRequest->salesmanId,
                'toBeEmailed' => $updateSalesOrderRequest->toBeEmailed,
                'toBePrinted' => $updateSalesOrderRequest->toBePrinted,
            ]
        );

        $currencyData = [
            'activeFlag' => $updateSalesOrderRequest->activeFlag,
            'code' => $updateSalesOrderRequest->currencyCode,
            'excludeFromUpdate' => $updateSalesOrderRequest->excludeFromUpdate,
            'homeCurrency' => $updateSalesOrderRequest->homeCurrency,
            'symbol' => $updateSalesOrderRequest->currencySymbol,
        ];

        $currencyRequest = new Request($currencyData);
        $currencyController = new CurrencyController();
        $currencyResponse = $currencyController($currencyRequest)->getData();

        // SALES ORDER ITEM TYPE
        $salesOrderItemType = SalesOrderItemType::firstOrCreate(['name' => $updateSalesOrderRequest->salesOrderItemTypeName]);

        $product = Product::updateOrCreate(
            ['id' => $salesOrder->productId],
            [
                'defaultSoItemType' => $salesOrderItemType->id,
                'details' => $updateSalesOrderRequest->productDetails,
            ]
        );

        $taxRateData = [
            'name' => $updateSalesOrderRequest->taxRateName,
            'activeFlag' => $updateSalesOrderRequest->activeFlag,
            'code' => $updateSalesOrderRequest->taxRateCode,
            'defaultFlag' => $updateSalesOrderRequest->defaultFlag,
            'description' => $updateSalesOrderRequest->taxRateDescription,
            'orderTypeId' => $salesOrderItemType->id,
            'rate' => $updateSalesOrderRequest->taxRate,
            'taxAccountId' => $account->id,
        ];

        $taxRateRequest = new Request($taxRateData);
        $taxRateController = new TaxRateController();
        $taxRateResponse = $taxRateController($taxRateRequest)->getData();

        $paymentTermsData = [
            'activeFlag' => $updateSalesOrderRequest->activeFlag,
            'defaultTerm' => $updateSalesOrderRequest->defaultTerm,
            'name' => $updateSalesOrderRequest->paymentTermsName,
            'discount' => $updateSalesOrderRequest->discount,
            'discountDays' => $updateSalesOrderRequest->discountDays,
            'netDays' => $updateSalesOrderRequest->netDays,
            'nextMonth' => $updateSalesOrderRequest->nextMonth,
            'readOnly' => $updateSalesOrderRequest->readOnly,
        ];

        $paymentTermsRequest = new Request($paymentTermsData);
        $paymentTermsController = new PaymentTermsController();
        $paymentTermsResponse = $paymentTermsController($paymentTermsRequest)->getData();

        $priority = Priority::firstOrCreate(['name' => $updateSalesOrderRequest->priorityName]);

        $qbclass = qbClass::firstOrCreate(
            [
                'name' => $updateSalesOrderRequest->quickBookName,
                'activeFlag' => $updateSalesOrderRequest->activeFlag,
            ]
        );

        $salesOrder->update(
            $updateSalesOrderRequest->except(
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
                'customerId' => $customer->id,
                'billToCountryId' => $addressResponse->billToCountryId,
                'billToStateId' => $addressResponse->billToStateId,
                'carrierId' => $carrierResponse->carrierId,
                'carrierServiceId' => $carrierResponse->carrierServiceId,
                'currencyId' => $currencyResponse->id,
                'locationGroupId' => $locationGroupResponse->id,
                'paymentTermsId' => $paymentTermsResponse->id,
                'priorityId' => $priority->id,
                'qbClassId' => $qbclass->id,
                'shipToCountryId' => $addressResponse->shipToCountryId,
                'shipToStateId' => $addressResponse->shipToStateId,
                'taxRateId' => $taxRateResponse->id,
            ]
        );

        // SALES ORDER STATUS
        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $updateSalesOrderRequest->salesOrderStatus]);

        // SALES ORDER ITEMS
        $salesOrderItemData = [
            'productId' => $product->id,
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
            'products' => $product,
            'salesOrderItem' => $salesOrderItemResponse,
            'message' => 'Success',
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
