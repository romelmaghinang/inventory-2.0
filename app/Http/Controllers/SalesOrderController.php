<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SalesOrderController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:10,20,95',
            'CustomerName' => 'required|string',
            'billToAddress' => 'required|string',
            'billToCity' => 'required|string',
            'billToCountryId' => 'required|string',
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
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'SONum', 'status', 'CustomerContact', 'billToAddress',
            'billToCity', 'billToCountryId', 'billToName', 'billToStateId', 'billToZip',
            'carrierId', 'carrierServiceId', 'Cost', 'currencyId', 'currencyRate', 'customerId',
            'customerPO', 'dateCompleted', 'dateExpired', 'dateFirstShip',
            'dateIssued', 'dateLastModified', 'dateRevision', 'email', 'estimatedTax',
            'fobPointId', 'locationGroupId', 'mcTotalTax', 'note', 'num', 'paymentTermsId',
            'phone', 'priorityId', 'qbClassId', 'registerId', 'residentialFlag', 'revisionNum',
            'salesman', 'salesmanId', 'salesmanInitials', 'shipTermsId', 'shipToAddress',
            'shipToCity', 'shipToCountryId', 'shipToName', 'shipToStateId', 'shipToZip',
            'statusId', 'taxRate', 'taxRateId', 'taxRateName', 'toBeEmailed', 'toBePrinted',
            'totalIncludesTax', 'totalTax', 'subTotal', 'totalPrice', 'typeId', 'url',
            'username', 'vendorPO'
        ]);

        // Find the highest SONum and increment it
        $prefix = 10;
        $lastOrder = SalesOrder::orderBy('id', 'desc')->first();
        $lastNumber = $lastOrder ? intval(substr($lastOrder->num, strlen($prefix))) : 0;
        $newNumber = $lastNumber + 1;
        $data['num'] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Use today's date for dateCreate
        $data['dateCreate'] = Carbon::now()->toDateString();

        // Find or create customer
        $customerController = new CustomerController();
        $customer = $customerController->findOrCreateCustomer($request->CustomerName, 'so');
        $data['CustomerName'] = $customer->so;

        $salesOrder = SalesOrder::create($data);

        return response()->json([
            'message' => 'Sales Order created successfully', 'sales_order' => $salesOrder, 'all_fields' => $data]);
    }
}
