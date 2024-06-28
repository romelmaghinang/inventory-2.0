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

        // Find or create customer using Customer Controller
        $customerController = new CustomerController();
        $customer = $customerController->findOrCreateCustomer($request);
        if ($customer instanceof \Illuminate\Http\JsonResponse) {
            return $customer;
        }
        $data['customerId'] = $customer->id;

        /* still to be modified */
        $carrierController = new CarrierController();
        $data['carrierId'] = $carrierController->getCarrierId($request->carrierName);
        $data['carrierServiceId'] = $carrierController->getCarrierServiceId($request->carrierServiceName);

        $currencyController = new CurrencyController();
        $data['currencyId'] = $currencyController->getCurrencyId($request->currencyName);
        $data['currencyRate'] = $currencyController->getCurrencyRate($request->currencyName);

        $fobPointController = new FobPointController();
        $data['fobPointId'] = $fobPointController->getFobPointId($request->fobPointName);

        $locationController = new LocationController();
        $data['locationGroupId'] = $locationController->getLocationGroupId($request->locationGroupName);

        $paymentController = new PaymentController();
        $data['paymentTermsId'] = $paymentController->getPaymentTermsId($request->paymentTermsName);

        $priorityController = new PriorityController();
        $data['priorityId'] = $priorityController->getPriorityId($request->priorityName);

        $qbClassController = new QbClassController();
        $data['qbClassId'] = $qbClassController->getQbClassId($request->qbClassName);

        $registerController = new RegisterController();
        $data['registerId'] = $registerController->getRegisterId($request->registerName);

        $salesmanController = new SalesmanController();
        $salesmanData = $salesmanController->getSalesmanData($request->salesmanName);
        $data['salesmanId'] = $salesmanData['id'];
        $data['salesman'] = $salesmanData['name'];
        $data['salesmanInitials'] = $salesmanData['initials'];

        $shippingController = new ShippingController();
        $shippingData = $shippingController->getShippingData($request->shippingTermName);
        $data['shipTermsId'] = $shippingData['shipTermsId'];
        $data['shipToCountryId'] = $shippingData['shipToCountryId'];
        $data['shipToStateId'] = $shippingData['shipToStateId'];

        $taxController = new TaxController();
        $taxData = $taxController->getTaxRateData($request->taxRateName);
        $data['taxRateId'] = $taxData['taxRateId'];
        $data['taxRateName'] = $taxData['taxRateName'];

        // Create the sales order
        $salesOrder = SalesOrder::create([
            'billToAddress' => $request->input('billToAddress'),
            'billToCity' => $request->input('billToCity'),
            'billToCountryId' => $request->input('billToCountryId'),
            'billToName' => $request->input('billToName'),
            'billToStateId' => $request->input('billToStateId'),
            'billToZip' => $request->input('billToZip'),
            'carrierId' => $data['carrierId'],
            'carrierServiceId' => $data['carrierServiceId'],
            'cost' => $request->input('cost'),
            'currencyId' => $data['currencyId'],
            'currencyRate' => $data['currencyRate'],
            'customerContact' => $request->input('customerContact'),
            'customerId' => $request->input('customerId'),
            'customerPO' => $request->input('customerPO'),
            'dateCompleted' => $request->input('dateCompleted'),
            'dateCreated' => $request->input('dateCreated'),
            'dateExpired' => $request->input('dateExpired'),
            'dateFirstShip' => $request->input('dateFirstShip'),
            'dateIssued' => $request->input('dateIssued'),
            'dateLastModified' => $request->input('dateLastModified'),
            'dateRevision' => $request->input('dateRevision'),
            'email' => $request->input('email'),
            'estimatedTax' => $request->input('estimatedTax'),
            'fobPointId' => $data['fobPointId'],
            'locationGroupId' => $data['locationGroupId'],
            'mcTotalTax' => $request->input('mcTotalTax'),
            'note' => $request->input('note'),
            'num' => $orderNum,
            'paymentTermsId' => $data['paymentTermsId'],
            'phone' => $request->input('phone'),
            'priorityId' => $data['priorityId'],
            'qbClassId' => $data['qbClassId'],
            'registerId' =>  $data['registerId'],
            'residentialFlag' => $request->input('residentialFlag'),
            'revisionNum' => $request->input('revisionNum'),
            'salesman' => $data['salesman'],
            'salesmanId' => $data['salesmanId'],
            'salesmanInitials' => $data['salesmanInitials'],
            'shipTermsId' => $data['shipTermsId'],
            'shipToAddress' => $request->input('shipToAddress'),
            'shipToCity' => $request->input('shipToCity'),
            'shipToCountryId' => $data['shipToCountryId'],
            'shipToName' => $request->input('shipToName'),
            'shipToStateId' => $data['shipToStateId'],
            'shipToZip' => $request->input('shipToZip'),
            'statusId' => $request->input('statusId'),
            'taxRate' => $request->input('taxRate'),
            'taxRateId' => $data['taxRateId'],
            'taxRateName' => $data['taxRateName'],
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

