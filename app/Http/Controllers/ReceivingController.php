<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
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

            $receiptItem = null;

            if (!empty($validatedData['VendorPartNum'])) {
                $purchaseOrderItem = PurchaseOrderItem::where('vendorPartNum', $validatedData['VendorPartNum'])
                    ->where('poId', $purchaseOrder->id)
                    ->firstOrFail();

                $receiptItem = ReceiptItem::where('receiptId', $receipt->id)
                    ->where('poItemId', $purchaseOrderItem->id)
                    ->firstOrFail();

                $receiptItem->update([
                    'qtyReceived' => $validatedData['Qty'] ?? $receiptItem->qtyReceived,
                    'location' => $validatedData['Location'] ?? $receiptItem->location,
                    'dateReceived' => !empty($validatedData['Date']) ? Carbon::parse($validatedData['Date'])->toDateTimeString() : $receiptItem->dateReceived,
                    'trackingNumber' => $validatedData['ShippingTrackingNumber'] ?? $receiptItem->trackingNumber,
                    'packageCount' => $validatedData['ShippingPackageCount'] ?? $receiptItem->packageCount,
                    'carrier' => $validatedData['ShippingCarrier'] ?? $receiptItem->carrier,
                    'carrierService' => $validatedData['ShippingCarrierService'] ?? $receiptItem->carrierService,
                    'dateLastModified' => Carbon::now(),
                ]);
            }

            $receipt->update([
                'dateLastModified' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Receiving successfully',
                'receiptData' => $receipt,
                'receiptItemData' => $receiptItem,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Purchase Order, Receipt, or Receipt Item not found.'], Response::HTTP_NOT_FOUND);
        }
    }
}
