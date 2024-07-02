<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    public function create(Request $request)
    {
        //Validate the request data
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:10,20,95',
            'customerName' => 'required|string',
            'customerContact' => 'nullable|string',
            'billToAddress' => 'required|string',
            'billToCity' => 'required|string',
            'billToName' => 'required|string',
            'billToZip' => 'required|string',
            'dateFirstShip' => 'required|date',
            'shipToAddress' => 'required|string',
            'shipToCity' => 'required|string',
            'shipToName' => 'required|string',
            'shipToZip' => 'required|string',
            'taxRateName' => 'required|string',
            //Added other fields for sales order creation
            'carrierName' => 'nullable|string',
            'carrierServiceName' => 'nullable|string',
            'billToCountryName' => 'nullable|string',
            'currencyName' => 'nullable|string',
            'locationGroupName' => 'nullable|string',
            'paymentTermsName' => 'nullable|string',
            'priorityName' => 'nullable|string',
            'qbClassName' => 'nullable|string',
            'salesmanName' => 'nullable|string',
            'shippingTermName' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate Sales Order Number
        $prefix = 10;
        $lastOrder = SalesOrder::orderBy('id', 'desc')->first();
        $lastNumber = $lastOrder ? intval(substr($lastOrder->num, strlen($prefix))) : 0;
        $newNumber = $lastNumber + 1;
        $orderNum = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Retrieve or create the billToCountryId and billToStateId
        $AddressController = new AddressController();
        $addressResponse = $AddressController->getOrCreateAddress($request);
        $billToCountryId = $addressResponse->getData()->countryId;
        $billToStateId = $addressResponse->getData()->stateId;

        /* Find or create customer using Customer Controller */
        $customerController = new CustomerController();
        $customer = $customerController->getOrCreateCustomer($request);
        $customerId = $customer->getData()->id;

        // Retrieve or create the carrier ID using CarrierController
        $carrierController = new CarrierController();
        $carrierIdResponse = $carrierController->getCarrierId($request);
        $carrierId = $carrierIdResponse->getData()->carrierId;

        // Retrieve or create the carrier ID using CarrierController
        $carrierServiceController = new CarrierServiceController();
        $carrierServiceIdResponse = $carrierServiceController->getCarrierServiceId($request);
        $carrierServiceId = $carrierServiceIdResponse->getData()->carrierServiceId;

        $currencyController = new CurrencyController();
        $currencyIdResponse = $currencyController->getCurrency($request);
        $currencyRateResponse = $currencyController->getCurrency($request);
        $currencyId = $currencyIdResponse->getData()->currencyId;
        $currencyRate = $currencyRateResponse->getData()->currencyRate;

        $locationController = new LocationController();
        $locationGroupIdResponse = $locationController->getLocationGroup($request);
        $locationGroupId = $locationGroupIdResponse->getData()->locationGroupId;

        $paymentController = new PaymentController();
        $paymentTermsIdResponse = $paymentController->getPaymentTerm($request);
        $paymentTermsId = $paymentTermsIdResponse->getData()->paymentTermId;

        $priority = new PriorityController();
        $priorityIdResponse = $priority->getPriorityId($request);
        $priorityId = $priorityIdResponse->getData()->priorityId;

        $qbClassController = new qbClassController();
        $qbClassIdResponse = $qbClassController->getQbClassId($request);
        $qbClassId = $qbClassIdResponse->getData()->qbClassId;

        $salesmanController = new SalesmanController();
        $salesmanData = $salesmanController->getSalesmanData($request);
        $salesmanId = $salesmanData->getData()->salesmanId;
        $salesmanName = $salesmanData->getData()->salesmanName;
        $salesmanInitials = $salesmanData->getData()->salesmanInitials;

        $shippingController = new ShippingController();
        $shippingData = $shippingController->getShippingData($request);
        $shipTermsId = $shippingData->getData()->shippingTermsId;
        $shipToCountryId = $shippingData->getData()->shipToCountryId;
        $shipToStateId = $shippingData->getData()->shipToStateId;
        $statusId = $shippingData->getData()->statusId;

        $taxController = new TaxController();
        $taxData = $taxController->getTaxRateData($request);
        $taxRateId = $taxData->getData()->taxRateId;
        $taxRateName = $taxData->getData()->taxRateName;

        //Create the sales order
        $salesOrderData = $request->all();
        $salesOrderData['billToCountryId'] = $billToCountryId;
        $salesOrderData['billToStateId'] =  $billToStateId;
        $salesOrderData['carrierId'] = $carrierId;
        $salesOrderData['carrierServiceId'] = $carrierServiceId;
        $salesOrderData['customerId'] = $customerId;
        $salesOrderData['currencyId'] = $currencyId;
        $salesOrderData['currencyRate'] = $currencyRate;
        $salesOrderData['locationGroupId'] = $locationGroupId;
        $salesOrderData['num'] = $orderNum;
        $salesOrderData['paymentTermsId'] = $paymentTermsId;
        $salesOrderData['priorityId'] = $priorityId;
        $salesOrderData['qbClassId'] = $qbClassId;
        $salesOrderData['salesmanName'] = $salesmanName;
        $salesOrderData['salesmanId'] = $salesmanId;
        $salesOrderData['salesmanInitials'] = $salesmanInitials;
        $salesOrderData['shipTermsId'] = $shipTermsId;
        $salesOrderData['shipToCountryId'] = $shipToCountryId;
        $salesOrderData['shipToStateId'] = $shipToStateId;
        $salesOrderData['statusId'] = $statusId;
        $salesOrderData['taxRateId'] = $taxRateId;
        $salesOrderData['taxRateName'] = $taxRateName;

        $salesOrder = SalesOrder::create($salesOrderData);

        return response()->json([
            'message' => 'Sales Order created successfully',
            'so' => $salesOrder
        ]);
    }
}

