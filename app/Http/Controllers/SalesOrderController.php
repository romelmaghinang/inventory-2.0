<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Location;
use App\Models\PaymentTerms;
use App\Models\Priority;
use App\Models\qbClass;
use App\Models\Salesman;
use App\Models\SalesOrder;
use App\Models\Shipping;
use App\Models\State;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SalesOrderController extends Controller
{
    public function create(Request $request): JsonResponse
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

        /* get or create the billToCountryId and billToStateId */
        $Address = new AddressController();
        $addressResponse = $Address->getOrCreateAddress($request);
        $billToCountryId = $addressResponse->getData()->countryId;
        $billToStateId = $addressResponse->getData()->stateId;

        /* Find or create customer using Customer Controller */
        $customerController = new CustomerController();
        $customer = $customerController->getOrCreateCustomer($request);
        $customerId = $customer->getData()->id;

        // Retrieve carrier ID using Carrier
        $carrier = new CarrierController();
        $carrierIdResponse = $carrier->getCarrierId($request);
        $carrierId = $carrierIdResponse->getData()->carrierId;

        // Retrieve carrierServiceID using CarrierServiceController
        $carrierService = new CarrierServiceController();
        $carrierServiceIdResponse = $carrierService->getCarrierServiceId($request);
        $carrierServiceId = $carrierServiceIdResponse->getData()->carrierServiceId;

        $currencyController = new CurrencyController();
        $currencyIdResponse = $currencyController->getCurrency($request);
        $currencyRateResponse = $currencyController->getCurrency($request);
        $currencyId = $currencyIdResponse->getData()->currencyId;
        $currencyRate = $currencyRateResponse->getData()->currencyRate;

        $location = new LocationController();
        $locationGroupIdResponse = $location->getLocationGroup($request);
        $locationGroupId = $locationGroupIdResponse->getData()->locationGroupId;

        $payment = new PaymentController();
        $paymentTermsIdResponse = $payment->getPaymentTerm($request);
        $paymentTermsId = $paymentTermsIdResponse->getData()->paymentTermId;

        $priority = new PriorityController();
        $priorityIdResponse = $priority->getPriorityId($request);
        $priorityId = $priorityIdResponse->getData()->priorityId;

        $qbClass = new qbClassController();
        $qbClassIdResponse = $qbClass->getQbClassId($request);
        $qbClassId = $qbClassIdResponse->getData()->qbClassId;

        $salesman = new SalesmanController();
        $salesmanData = $salesman->getSalesmanData($request);
        $salesmanId = $salesmanData->getData()->salesmanId;
        $salesmanName = $salesmanData->getData()->salesmanName;
        $salesmanInitials = $salesmanData->getData()->salesmanInitials;

        $shipping = new ShippingController();
        $shippingData = $shipping->getShippingData($request);
        $shipTermsId = $shippingData->getData()->shippingTermsId;
        $shipToCountryId = $shippingData->getData()->shipToCountryId;
        $shipToStateId = $shippingData->getData()->shipToStateId;
        $statusId = $shippingData->getData()->statusId;

        $tax = new TaxController();
        $taxData = $tax->getTaxRateData($request);
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

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:10,20,95',
            'customerName' => 'required|string',
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

        //find the SalesOrder
        $salesOrder = SalesOrder::find($id);
        if (!$salesOrder) {
            return response()->json(['message' => 'Sales Order not found'], 404);
        }

        $address = new Address();
        $state = new State();
        $carrier = new Carrier();
        $carrierService = new CarrierService();
        $customer = new Customer();
        $currency = new Currency();
        $location = new Location();
        $paymentTerms = new PaymentTerms();
        $priority = new Priority();
        $qbClass = new qbClass();
        $salesman = new Salesman();
        $shipping = new Shipping();
        $tax = new Tax();

        //retrieve necessary data using models
        $billToCountryId = $address->getCountryIdByName($request->input('billToCountryName'));
        $carrierId = $carrier->getCarrierId($request->input('name'));
        $carrierServiceId = $carrierService->getCarrierServiceId($request->input('code'), $request->input('name'));
        $currencyData = $currency->getCurrency($request->input('code'));
        $customerId = $customer->getOrCreateCustomer($request->input('customerName'));
        $currencyId = $currencyData['id'];
        $locationGroupId = $location->getLocationGroup($request->input('name'));
        $paymentTermsId = $paymentTerms->getPaymentTermsId($request->input('name'));
        $priorityId = $priority->getPriorityIdByName($request->input('priorityName'));
        $salesmanData = $salesman->getSalesmanData($request->input('salesmanId'));
        $salesmanId = $salesmanData['salesmanId'];
        $salesmanName = $salesmanData['salesmanName'];
        $salesmanInitials = $salesmanData['salesmanInitials'];
        $shipToCountryId = $address->getCountryIdByName($request->input('shipToCountryName'));
        $shipToStateId = $state->getStateIdByName($request->input('shipToStateName'));
        $shippingDetails = $shipping->getShippingData($request->input('contact'), $shipToCountryId, $shipToStateId);
        $shipTermsId = $shippingDetails['shipTermsId'];
        $taxRateDetails = $tax->getTaxRateData($request->input('taxRateName'));
        $taxRateId = $taxRateDetails['taxRateId'];
        $taxRateName = $taxRateDetails['taxRateName'];

        //update sales order
        $salesOrder->status = $request->input('status');
        $salesOrder->customerId = $customerId;
        $salesOrder->billToAddress = $request->input('billToAddress');
        $salesOrder->billToCity = $request->input('billToCity');
        $salesOrder->billToCountryId = $billToCountryId;
        $salesOrder->billToName = $request->input('billToName');
        $salesOrder->billToStateId = $request->input('billToStateId');
        $salesOrder->billToZip = $request->input('billToZip');
        $salesOrder->dateFirstShip = $request->input('dateFirstShip');
        $salesOrder->shipToAddress = $request->input('shipToAddress');
        $salesOrder->shipToCity = $request->input('shipToCity');
        $salesOrder->shipToName = $request->input('shipToName');
        $salesOrder->shipToZip = $request->input('shipToZip');
        $salesOrder->taxRateId = $taxRateId;
        $salesOrder->taxRateName = $taxRateName;
        $salesOrder->carrierId = $carrierId;
        $salesOrder->carrierServiceId = $carrierServiceId;
        $salesOrder->currencyId = $currencyId;
        $salesOrder->locationGroupId = $locationGroupId;
        $salesOrder->paymentTermsId = $paymentTermsId;
        $salesOrder->priorityId = $priorityId;
        $salesOrder->qbClassId = $qbClass;
        $salesOrder->salesmanId = $salesmanId;
        $salesOrder->salesmanName = $salesmanName;
        $salesOrder->salesmanInitials = $salesmanInitials;
        $salesOrder->shipTermsId = $shipTermsId;

        $salesOrder->save();

        return response()->json([
            'message' => 'Sales Order updated successfully',
            'sales_order' => $salesOrder
        ]);
    }
}

