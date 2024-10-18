<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Location;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Requests\Receiving\ReceivingRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ReceivingController extends Controller
{
   /**
     * @OA\Post(
     *     path="/api/receiving",
     *  
     *     tags={"Receiving"},
     *     summary="Receives parts from a purchase order.",
     *     description="Endpoint to receive parts from a purchase order into inventory.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="PONum", type="string", maxLength=25, example="123", description="The purchase order number used to order the received parts."),
     *                 @OA\Property(property="Fulfill", type="boolean", example=true, description="Indicates whether the line item will be fulfilled or received only."),
     *                 @OA\Property(property="VendorPartNum", type="string", maxLength=50, example="1001", description="The vendor part number."),
     *                 @OA\Property(property="Qty", type="integer", example=100, description="The quantity of parts received."),
     *                 @OA\Property(property="Location", type="string", example="Main", description="The location to receive the parts into."),
     *                 @OA\Property(property="Date", type="string", format="date", example="2024-10-09", description="The date the parts were received."),
     *                 @OA\Property(property="ShippingTrackingNumber", type="string", example="1001", description="The shipping tracking number."),
     *                 @OA\Property(property="ShippingPackageCount", type="integer", example=5, description="The package count for the shipment."),
     *                 @OA\Property(property="ShippingCarrier", type="string", example="Delivery", description="The carrier used for shipping."),
     *                 @OA\Property(property="ShippingCarrierService", type="string", example="Express Saver", description="The shipping carrier's service level.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receiving details successfully recorded."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request. Missing or incorrect parameters."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    public function receiving(ReceivingRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $purchaseOrder = PurchaseOrder::where('num', $validatedData['PONum'])->firstOrFail();

            $purchaseOrderItems = PurchaseOrderItem::where('poId', $purchaseOrder->id)->get();

            if ($purchaseOrderItems->isEmpty()) {
                return response()->json(['error' => 'No Purchase Order Items found.'], Response::HTTP_NOT_FOUND);
            }

            foreach ($purchaseOrderItems as $poItem) {
                $inventory = Inventory::where('partId', $poItem->partId)->first();

                if ($inventory) {
                    $inventory->qtyOnHand += $validatedData['Qty'];
                    $inventory->changeQty += $validatedData['Qty'];
                    $inventory->save();
                } else {
                    return response()->json(['error' => 'Inventory item not found for partid ' . $poItem->partid], Response::HTTP_NOT_FOUND);
                }
            }

            $receipt = Receipt::where('poId', $purchaseOrder->id)->firstOrFail();
            $receiptItem = ReceiptItem::where('receiptId', $receipt->id)->firstOrFail();

            $receiptItem->update([
                'qty' => $validatedData['Qty'] ?? $receiptItem->qtyReceived,
                'statusId' => 30,
                'dateReceived' => !empty($validatedData['Date']) ? Carbon::parse($validatedData['Date'])->toDateTimeString() : $receiptItem->dateReceived,
                'trackingNum' => $validatedData['ShippingTrackingNumber'] ?? $receiptItem->trackingNumber,
                'packageCount' => $validatedData['ShippingPackageCount'] ?? $receiptItem->packageCount,
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
/**
 * @OA\Delete(
 *     path="/api/receipt-void/{receiptItemId}",
 *     tags={"Receipt"},
 *     summary="Void a receipt item",
 *     description="Deletes (voids) a receipt item by its ID.",
 *     @OA\Parameter(
 *         name="receiptItemId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the receipt item to be voided",
 *         example=1
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receipt Item Void successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Receipt Item Void successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(
 *             @OA\Property(property="errors", type="object", description="Validation errors")
 *         )
 *     )
 * )
 */

    public function delete (Request $request): JsonResponse
    {
        $deleteRequest = Validator::make(
            $request->all(),
            [
                'receiptItemId' => ['required', 'numeric', 'exists:receiptitem,id']
            ]
        );

        if ($deleteRequest->fails()) {
            return response()->json(['errors' => $deleteRequest->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $deleteRequest->validated();

        $receiptItem = ReceiptItem::findOrFail($validatedData['receiptItemId']);

        $receiptItem->delete();

        return response()->json(
            [
                'message' => 'Receipt Item Void successfully',
            ],
            JsonResponse::HTTP_OK
        );
    }
}
