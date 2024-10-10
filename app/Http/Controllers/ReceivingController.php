<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Location; 
use Illuminate\Http\Request;
use App\Http\Requests\Receiving\ReceivingRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReceivingController extends Controller
{
    public function receiving(ReceivingRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $purchaseOrder = PurchaseOrder::where('num', $validatedData['PONum'])->firstOrFail();

            $receipt = Receipt::where('poId', $purchaseOrder->id)->firstOrFail();

            $receiptItem = ReceiptItem::where('receiptId', $receipt->id)->firstOrFail();

            $receiptItem->update([
                'qty' => $validatedData['Qty'] ?? $receiptItem->qtyReceived,
                'dateReceived' => !empty($validatedData['Date']) ? Carbon::parse($validatedData['Date'])->toDateTimeString() : $receiptItem->dateReceived,
                'trackingNum' => $validatedData['ShippingTrackingNumber'] ?? $receiptItem->trackingNumber,
                'packageCount' => $validatedData['ShippingPackageCount'] ?? $receiptItem->packageCount,
                'dateLastModified' => Carbon::now(),
            ]);

            $receipt->update([
                'dateLastModified' => Carbon::now(),
            ]);

            if (!empty($validatedData['Location'])) {
                $location = Location::where('name', $validatedData['Location'])->first();
                if (!$location) {
                    return response()->json(['error' => 'Location not found'], Response::HTTP_NOT_FOUND);
                }
                $receiptItem->locationId = $location->id;
            }

            if (!empty($validatedData['ShippingCarrier'])) {
                $carrier = Carrier::where('name', $validatedData['ShippingCarrier'])->first();
                if ($carrier) {
                    $receiptItem->carrierId = $carrier->id;
                }
            }

            if (!empty($validatedData['ShippingCarrierService'])) {
                $carrierService = CarrierService::where('name', $validatedData['ShippingCarrierService'])->first();
                if ($carrierService) {
                    $receiptItem->carrierServiceId = $carrierService->id;
                }
            }

            $receiptItem->save();

            return response()->json([
                'message' => 'Receiving successfully',
                'receiptData' => $receipt,
                'updatedReceiptItem' => $receiptItem->fresh(), 
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Purchase Order, Receipt, or Receipt Item not found.'], Response::HTTP_NOT_FOUND);
        }
    }
}
