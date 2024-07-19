<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carrier\StoreCarrierRequest;
use App\Http\Requests\Carrier\UpdateCarrierRequest;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Requests\Payment\StorePaymentTermsRequest;
use App\Http\Requests\Payment\UpdatePaymentTermsRequest;
use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Http\Requests\SalesOrderItem\StoreSalesOrderItemRequest;
use App\Http\Requests\SalesOrderItem\UpdateSalesOrderItemRequest;
use App\Http\Requests\TaxRate\StoreTaxRateRequest;
use App\Http\Requests\TaxRate\UpdateTaxRateRequest;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Customer;
use App\Models\Priority;
use App\Models\Product;
use App\Models\qbClass;
use App\Models\SalesOrder;
use App\Models\SalesOrderItemType;
use App\Models\SalesOrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function store(
        StoreSalesOrderRequest $storeSalesOrderRequest,
        StoreLocationRequest $storeLocationRequest,
        StoreCarrierRequest $storeCarrierRequest,
        StoreCustomerRequest $storeCustomerRequest,
        StoreTaxRateRequest $storeTaxRateRequest,
        StoreSalesOrderItemRequest $storeSalesOrderItemRequest,
        StorePaymentTermsRequest $storePaymentTermsRequest,
        StoreCurrencyRequest $storeCurrencyRequest,
    ): JsonResponse {

        $accountType = AccountType::firstOrCreate(['name' => $storeSalesOrderRequest->accountTypeName]);
        $account = Account::firstOrCreate(['typeId' => $accountType->id]);

        $locationGroupData =
            [
                'locationGroupName' => $storeLocationRequest->locationGroupName,
                'countedAsAvailable' => $storeLocationRequest->countedAsAvailable,
                'locationName' => $storeLocationRequest->locationName,
                'pickable' => $storeLocationRequest->pickable,
                'receivable' => $storeLocationRequest->receivable,
                'sortOrder' => $storeLocationRequest->sortOrder,
                'typeId' => $account->id,
            ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

        $addressData =
            [
                'accountId' => $account->id,
                'billToName' => $storeSalesOrderRequest->billToName,
                'billToCity' => $storeSalesOrderRequest->billToCity,
                'billToCountryName' => $storeSalesOrderRequest->billToCountryName,
                'locationGroupId' => $locationGroupResponse->locationGroupId,
                'billToAddress' => $storeSalesOrderRequest->billToAddress,
                'billToStateName' => $storeSalesOrderRequest->billToStateName,
                'billToZip' => $storeSalesOrderRequest->billToZip,
                'defaultFlag' => $storeSalesOrderRequest->defaultFlag,
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
            'carrierServiceName' => $storeCarrierRequest->carrierServiceName,
            'readOnly' => $storeCarrierRequest->readOnly,
            'scac' => $storeCarrierRequest->scac,
            'carrierDescription' => $storeCarrierRequest->carrierDescription,
            'carrierCode' => $storeCarrierRequest->carrierCode,
        ];

        $carrierRequest = new Request($carrierData);
        $carrierController = new CarrierController();
        $carrierResponse = $carrierController($carrierRequest)->getData();

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
                'name' => $storeCurrencyRequest->currencyName,
                'code' => $storeSalesOrderRequest->currencyCode,
                'homeCurrency' => $storeSalesOrderRequest->homeCurrency,
                'symbol' => $storeSalesOrderRequest->currencySymbol,
            ];

        $currencyRequest = new Request($currencyData);
        $currencyController = new CurrencyController();
        $currencyResponse = $currencyController($currencyRequest)->getData();

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
                'code' => $storeSalesOrderRequest->taxRateCode,
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
                'name' => $storeSalesOrderRequest->quickBookName
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
                    'shipToStateName'
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

        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $storeSalesOrderRequest->salesOrderStatus]);

        $salesOrderItemData =
            [
                'productId' => $product->id,
                'note' => $storeSalesOrderRequest->note,
                'soLineItem' => $storeSalesOrderRequest->salesOrderLineItem,
                'soId' => $salesOrder->id,
                'statusId' => $salesOrderStatus->id,
                'typeId' => $salesOrderItemType->id,
            ];

        $salesOrderItemRequest = new Request($salesOrderItemData);
        $salesOrderItemController = new SalesOrderItemController();
        $salesOrderItemResponse = $salesOrderItemController($salesOrderItemRequest)->getData();

        return response()->json([
            'salesOrder' => $salesOrder,
            'salesOrderItem' => $salesOrderItemResponse,
            'message' => 'Success',
        ]);
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
    public function update(
        UpdateSalesOrderRequest $updateSalesOrderRequest,
        UpdateLocationRequest $updateLocationRequest,
        UpdateCarrierRequest $updateCarrierRequest,
        UpdateCustomerRequest $updateCustomerRequest,
        UpdateTaxRateRequest $updateTaxRateRequest,
        UpdateSalesOrderItemRequest $updateSalesOrderItemRequest,
        UpdatePaymentTermsRequest $updatePaymentTermsRequest,
        UpdateCurrencyRequest $updateCurrencyRequest,
        SalesOrder $salesOrder
    ): JsonResponse {

        $accountType = AccountType::firstOrCreate(['name' => $updateSalesOrderRequest->accountTypeName]);
        $account = Account::updateOrCreate(
            ['id' => $salesOrder->accountId],
            ['typeId' => $accountType->id]
        );

        $locationGroupData = [
            'locationGroupName' => $updateSalesOrderRequest->locationGroupName,
            'typeId' => $account->id,
        ];

        $locationGroupRequest = new Request($locationGroupData);
        $locationGroupController = new LocationGroupController();
        $locationGroupResponse = $locationGroupController($locationGroupRequest)->getData();

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
            'carrierDescription' => $updateSalesOrderRequest->carrierDescription,
            'carrierCode' => $updateSalesOrderRequest->carrierCode,
        ];

        $carrierRequest = new Request($carrierData);
        $carrierController = new CarrierController();
        $carrierResponse = $carrierController($carrierRequest)->getData();

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
            'name' => $updateCurrencyRequest->currencyName,
            'code' => $updateSalesOrderRequest->currencyCode,
            'excludeFromUpdate' => $updateCurrencyRequest->excludeFromUpdate,
            'homeCurrency' => $updateSalesOrderRequest->homeCurrency,
            'symbol' => $updateSalesOrderRequest->currencySymbol,
        ];

        $currencyRequest = new Request($currencyData);
        $currencyController = new CurrencyController();
        $currencyResponse = $currencyController($currencyRequest)->getData();

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

        $qbclass = qbClass::firstOrCreate(['name' => $updateSalesOrderRequest->quickBookName]);

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

        $salesOrderStatus = SalesOrderStatus::firstOrCreate(['name' => $updateSalesOrderRequest->salesOrderStatus]);

        $salesOrderItemData = [
            'productId' => $product->id,
            'note' => $updateSalesOrderRequest->note,
            'soLineItem' => $updateSalesOrderRequest->salesOrderLineItem,
            'soId' => $salesOrder->id,
            'statusId' => $salesOrderStatus->id,
            'typeId' => $salesOrderItemType->id,
        ];

        $salesOrderItemRequest = new Request($salesOrderItemData);
        $salesOrderItemController = new SalesOrderItemController();
        $salesOrderItemResponse = $salesOrderItemController($salesOrderItemRequest)->getData();

        return response()->json([
            'salesOrder' => $salesOrder,
            'salesOrderItem' => $salesOrderItemResponse,
            'message' => 'Success',
        ]);
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
