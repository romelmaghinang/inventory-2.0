<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            'SONum', 'status', 'CustomerName', 'CustomerContact', 'billToAddress',
            'billToCity', 'billToCountryId', 'billToName', 'billToStateId', 'billToZip',
            'carrierId', 'carrierServiceId', 'Cost', 'currencyId', 'currencyRate', 'customerId',
            'customerPO', 'dateCompleted', 'dateExpired', 'dateFirstShip',
            'dateIssued', 'dateLastModified', 'dateRevision', 'email', 'estimatedTax',
            'fobPointId', 'locationGroupId', 'mcTotalTax', 'note', 'Num', 'paymentTermsId',
            'phone', 'priorityId', 'qbClassId', 'registerId', 'residentialFlag', 'revisionNum',
            'salesman', 'salesmanId', 'salesmanInitials', 'shipTermsId', 'shipToAddress',
            'shipToCity', 'shipToCountryId', 'shipToName', 'shipToStateId', 'shipToZip',
            'statusId', 'taxRate', 'taxRateId', 'taxRateName', 'toBeEmailed', 'toBePrinted',
            'totalIncludesTax', 'totalTax', 'subTotal', 'totalPrice', 'typeId', 'url',
            'username', 'vendorPO'
        ]);

        // Auto-generate SONum if not provided
        if (empty($data['SONum'])) {
            $data['SONum'] = $this->generateSONum();
        }

        // Use today's date for dateCreate
        $data['dateCreate'] = Carbon::now()->toDateString();

        $salesOrder = SalesOrder::create($data);

        return response()->json(['message' => 'Sales Order created successfully', 'sales_order' => $salesOrder]);
    }

    private function generateSONum()
    {
        // Generate a unique Sales Order Number (SONum)
        return 'SO-' . strtoupper(\Illuminate\Support\Str::random(8));
    }
}
