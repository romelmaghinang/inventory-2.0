<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:10,20,95',
            'CustomerName' => 'required|string',
            'CustomerContact' => 'nullable|string',
            'billToAddress' => 'required|string',
            'billToCity' => 'required|string',
            'billToName' => 'required|string',
            'billToStateId' => 'required|string',
            'billToZip' => 'required|string',
            'dateFirstShip' => 'required|date',
            'shipToAddress' => 'required|string',
            'shipToCity' => 'required|string',
            'shipToCountryId' => 'required|string',
            'shipToName' => 'required|string',
            'shipToStateId' => 'required|string',
            'shipToZip' => 'required|string',
            'taxRateName' => 'required|string',
            // Added fields for customer creation
            'addressName' => 'nullable|string',
            'name' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'residentialFlag' => 'nullable|boolean',
            'locationGroup' => 'nullable|string',
            // Added other fields for sales order creation
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
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'status', 'CustomerContact', 'billToAddress', 'billToCity', 'billToName', 'billToStateId', 'billToZip',
            'dateFirstShip', 'shipToAddress', 'shipToCity', 'shipToCountryId', 'shipToName', 'shipToStateId',
            'shipToZip', 'taxRateName', 'addressName', 'customerName', 'street', 'city', 'state', 'zip', 'isResidential',
            'customerGroup', 'carrierName', 'carrierServiceName', 'billToCountryName', 'currencyName', 'fobPointName',
            'locationGroupName', 'paymentTermsName', 'priorityName', 'qbClassName', 'registerName', 'salesmanName',
            'shippingTermName'
        ]);

        // Find or create customer using Customer Controller
        $customerController = new CustomerController();
        $customer = $customerController->findOrCreateCustomer($request);
        $data['customerId'] = $customer->id;

        /* still to be modified
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
        $data['taxRateId'] = $taxController->getTaxRateId($request->taxRateName);
        */

        // Find the highest SONum (num) and increment it
        $prefix = 10;
        $lastOrder = SalesOrder::orderBy('id', 'desc')->first();
        $lastNumber = $lastOrder ? intval(substr($lastOrder->num, strlen($prefix))) : 0;
        $newNumber = $lastNumber + 1;
        $data['num'] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Use today's date for dateCreate
        $data['dateCreate'] = date('Y-m-d');

        // Create the sales order with Num
        $salesOrder = SalesOrder::create($data);

        return response()->json([
            'message' => 'Sales Order created successfully', 'sales_order' => $salesOrder, 'all_fields' => $data]);
    }
}
