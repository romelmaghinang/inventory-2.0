<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
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
            'fobPointName' => 'nullable|string',
            'locationGroupName' => 'nullable|string',
            'paymentTermsName' => 'nullable|string',
            'priorityName' => 'nullable|string',
            'qbClassName' => 'nullable|string',
            'registerName' => 'nullable|string',
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

        /* Find or create customer using Customer Controller
        $customerController = new CustomerController();
        $customer = $customerController->findOrCreateCustomer($request);
        $data['customerId'] = $customer->id; */

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

        /* this table does not exist in our database
        $fobPointController = new FobPointController();
        $data['fobPointId'] = $fobPointController->getFobPointId($request);
        */

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

        /* this table does not exist in our database
        $registerController = new RegisterController();
        $data['registerId'] = $registerController->getRegisterId($request);
        */

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

        // Create the sales order
        $salesOrder = SalesOrder::create([
            'billToAddress' => $request->input('billToAddress'),
            'billToCity' => $request->input('billToCity'),
            //'billToCountryId' => $billToCountryId,
            'billToName' => $request->input('billToName'),
            //'billToStateId' => $billToStateId,
            'billToZip' => $request->input('billToZip'),
            'carrierId' => $carrierId,
            'carrierServiceId' => $carrierServiceId,
            'cost' => $request->input('cost'),
            'currencyId' => $currencyId,
            'currencyRate' => $currencyRate,
            'customerContact' => $request->input('customerContact'),
            /*'customerId' => $customerId,
            'customerPO' => $customerPO,
            'dateCompleted' =>'$dateCompleted,
            'dateCreated' => $dateCreated,
            'dateExpired' => $dateExpired,
            'dateFirstShip' => $dateFirstShip,
            'dateIssued' => $dateIssued,
            'dateLastModified' => $dateLastModified,
            'dateRevision' => $dateRevision,
            'email' => $email,
            'estimatedTax' => $estimatedTax,
            'fobPointId' => $fobPointId, */
            'locationGroupId' => $locationGroupId,
            'mcTotalTax' => $request->input('mcTotalTax'),
            'note' => $request->input('note'),
            'num' => $orderNum,
            'paymentTermsId' => $paymentTermsId,
            'phone' => $request->input('phone'),
            'priorityId' => $priorityId,
            'qbClassId' => $qbClassId,
            //'registerId' =>  $data['registerId'],
            'residentialFlag' => $request->input('residentialFlag'),
            'revisionNum' => $request->input('revisionNum'),
            'salesman' => $salesmanName,
            'salesmanId' => $salesmanId,
            'salesmanInitials' => $salesmanInitials,
            'shipTermsId' => $shipTermsId,
            'shipToAddress' => $request->input('shipToAddress'),
            'shipToCity' => $request->input('shipToCity'),
            'shipToCountryId' => $shipToCountryId,
            'shipToName' => $request->input('shipToName'),
            'shipToStateId' => $shipToStateId,
            'shipToZip' => $request->input('shipToZip'),
            'statusId' => $statusId,
            'taxRate' => $request->input('taxRate'),
            'taxRateId' => $taxRateId,
            'taxRateName' => $taxRateName,
            'toBeEmailedy' => $request->input('toBeEmailedy'),
            'toBePrintedy' => $request->input('toBePrintedy'),
            'totalIncludesTaxy' => $request->input('totalIncludesTaxy'),
            'totalTax' => $request->input('totalTax'),
            'subTotal' => $request->input('subTotal'),
            'totalPrice' => $request->input('totalPrice'),
            'typeId' => $request->input('typeId'),
            'url' => $request->input('url'),
            'username' => $request->input('username'),
            'vendorPO' => $request->input('vendorPO'),
        ]);

        return response()->json([
            'message' => 'Sales Order created successfully',
            'so' => $salesOrder
        ]);
    }
}

