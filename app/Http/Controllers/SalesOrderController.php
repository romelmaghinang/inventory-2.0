<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SalesOrderController extends Controller
{
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest): JsonResponse
    {
        $addressData = [
            'name' => $storeSalesOrderRequest->name,
            'countryName' => $storeSalesOrderRequest->countryName,
            'stateName' => $storeSalesOrderRequest->stateName,
        ];
        $addressRequest = new Request($addressData);

        // Call AddressController and get response
        $addressController = new AddressController();
        $addressResponse = $addressController($addressRequest)->getData();

        // Prepare data for TaxController
        $taxData =
            [
                'taxRateName' => $storeSalesOrderRequest->taxRateName,
            ];

        $taxRequest = new Request($taxData);

        // Call TaxController and get response
        $taxController = new TaxController();
        $taxResponse = $taxController($taxRequest)->getData();

        $customerData =
            [
                'status' => $storeSalesOrderRequest->status,
                'name' => $storeSalesOrderRequest->name,
                'number' => $storeSalesOrderRequest->number,
                'taxExempt' => $storeSalesOrderRequest->taxExempt,
                'toBeEmailed' => $storeSalesOrderRequest->toBeEmailed,
                'toBePrinted' => $storeSalesOrderRequest->toBePrinted,
                'url' => $storeSalesOrderRequest->url,
            ];

        $customerRequest = new Request($customerData);

        $costumerController = new CustomerController();
        $costumerResponse = $costumerController($customerRequest)->getData();

        $carrierData = [
            'name' => $storeSalesOrderRequest->name,
            'description' => $storeSalesOrderRequest->description,
            'code' => $storeSalesOrderRequest->code,
        ];

        $carrierRequest = new Request($carrierData);

        $carrierController = new CarrierController();
        $carrierServiceResponse = $carrierController($carrierRequest)->getData();

        // Create SalesOrder
        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->except(['accountName', 'countryName', 'stateName', 'taxRateName']) +
            [
                'account_type_id' => $addressResponse->account_type_id,
                'country_id' => $addressResponse->country_id,
                'state_id' => $addressResponse->state_id,
                'tax_id' => $taxResponse->id,
                'customer_id' => $costumerResponse->id,
                'carrier_id' => $carrierServiceResponse->carrier_id,
                'carrier_service_id' => $carrierServiceResponse->id,
            ]
        );

        return response()->json([
            'message' => 'Sales order created successfully',
            'salesOrder' => $salesOrder
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
