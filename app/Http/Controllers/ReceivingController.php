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
                    return response()->json(['error' => 'Inventory item not found for partid ' . $poItem->partId], Response::HTTP_NOT_FOUND);
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
    
            $relatedData = [
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderItems' => $purchaseOrderItems,
                'receiptItems' => $receipt->items,
                'inventory' => Inventory::whereIn('partId', $purchaseOrderItems->pluck('partId'))->get(),
                'location' => $location ?? null,
                'carrier' => $carrier ?? null,
                'carrierService' => $carrierService ?? null,
            ];
    
            return response()->json([
                'message' => 'Receiving successfully',
                'receiptData' => $receipt,
                'updatedReceiptItem' => $receiptItem->fresh(),
                'relatedData' => $relatedData,
            ], Response::HTTP_OK);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Purchase Order, Receipt, or Receipt Item not found.'], Response::HTTP_NOT_FOUND);
        }
    }
    

  /**
 * @OA\Get(
 *     path="/api/receiving",
 *     tags={"Receiving"},
 *     summary="Get receipt items by tracking number",
 *     description="Retrieves receipt items associated with a specific tracking number (now called `num`). If no tracking number is provided, it retrieves all receipt items. Additionally, you can filter by creation date using `createdBefore` and `createdAfter`.",
 *     @OA\Parameter(
 *         name="num",
 *         in="query",
 *         description="Tracking number of the receipt to retrieve items for",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="createdBefore",
 *         in="query",
 *         description="Retrieve receipt items created before this date (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2024-12-31")
 *     ),
 *     @OA\Parameter(
 *         name="createdAfter",
 *         in="query",
 *         description="Retrieve receipt items created after this date (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2024-01-01")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="num", type="string", example="TN-12345", description="Tracking number of the receipt")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receipt items retrieved successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Receipt items retrieved successfully."),
 *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Receipt not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Receipt not found.")
 *         )
 *     )
 * )
 */
    public function getReceiptItemsByTrackingNum(Request $request): JsonResponse
    {
        $numFromQuery = $request->query('num');
        $numFromBody = $request->input('num');
        
        $createdBefore = $request->query('createdBefore');
        $createdAfter = $request->query('createdAfter');
        $perPage = $request->query('perPage', 100); 

        $idFromQuery = $request->query('id');

        $num = $numFromQuery ?? $numFromBody;

        if ($idFromQuery) {
            $receiptItem = ReceiptItem::find($idFromQuery);

            if (!$receiptItem) {
                return response()->json(['message' => 'Receipt item not found.'], Response::HTTP_NOT_FOUND);
            }

            $relatedData = [
                'receiptItem' => $receiptItem,
                'inventory' => Inventory::where('partId', $receiptItem->partId)->get(),
            ];

            return response()->json([
                'message' => 'Receipt item retrieved successfully.',
                'data' => $receiptItem,
                'relatedData' => $relatedData,
            ], Response::HTTP_OK);
        }

        if ($num) {
            $request->validate([
                'num' => 'required|string|exists:receipt,num',
            ]);

            $receipt = Receipt::where('num', $num)->first();

            if (!$receipt) {
                return response()->json(['message' => 'Receipt not found.'], Response::HTTP_NOT_FOUND);
            }

            $receiptItems = ReceiptItem::where('receiptId', $receipt->id);

            if ($createdBefore) {
                $request->validate(['createdBefore' => 'date|before_or_equal:today']);
                $receiptItems->whereDate('dateCreated', '<=', $createdBefore);
            }

            if ($createdAfter) {
                $request->validate(['createdAfter' => 'date|before_or_equal:today']);
                $receiptItems->whereDate('dateCreated', '>=', $createdAfter);
            }

            $relatedData = [
                'receipt' => $receipt,
                'receiptItems' => $receiptItems->paginate($perPage),
                'purchaseOrder' => PurchaseOrder::find($receipt->poId),
                'inventory' => Inventory::whereIn('partId', $receiptItems->pluck('partId'))->get(),
            ];

            return response()->json([
                'message' => 'Receipt items retrieved successfully.',
                'data' => $receiptItems->paginate($perPage),
                'relatedData' => $relatedData,
            ], Response::HTTP_OK);
        }

        $allReceiptItems = ReceiptItem::query();

        if ($createdBefore) {
            $request->validate(['createdBefore' => 'date|before_or_equal:today']);
            $allReceiptItems->whereDate('dateCreated', '<=', $createdBefore);
        }

        if ($createdAfter) {
            $request->validate(['createdAfter' => 'date|before_or_equal:today']);
            $allReceiptItems->whereDate('dateCreated', '>=', $createdAfter);
        }

        $relatedData = [
            'allReceiptItems' => $allReceiptItems->paginate($perPage),
            'inventory' => Inventory::whereIn('partId', $allReceiptItems->pluck('partId'))->get(),
        ];

        return response()->json([
            'message' => 'All receipt items retrieved successfully.',
            'data' => $allReceiptItems->paginate($perPage),
            'relatedData' => $relatedData,
        ], Response::HTTP_OK);
    }

 
 
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
