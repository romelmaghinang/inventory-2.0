<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryRequest;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartCost;
use App\Models\PartTracking;
use App\Models\Tag; 
use App\Models\UnitOfMeasure; 
use App\Models\Serial; 
use App\Models\SerialNum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/inventory",
 *     tags={"Inventory"},
 *     summary="Store a new inventory item",
 *     description="This endpoint allows the user to create a new inventory item or update an existing one based on the part number and location.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="PartNumber", type="string", example="10010", description="The unique number of the part."),
 *             @OA\Property(property="PartDescription", type="string", example="High-quality widget", description="A brief description of the part."),
 *             @OA\Property(property="Location", type="string", example="Main", description="The location where the part is stored."),
 *             @OA\Property(property="Qty", type="integer", example=5, description="The quantity of the part being added to inventory."),
 *             @OA\Property(property="UOM", type="string", example="Kilogram", description="The unit of measure for the part."),
 *             @OA\Property(property="Cost", type="number", format="float", example=25.50, description="The cost of the part."),
 *             @OA\Property(property="QbClass", type="string", example="Sales", description="The QuickBooks class for the part."),
 *             @OA\Property(property="Date", type="string", format="date", example="2024-09-17", description="The date when the inventory entry is created."),
 *             @OA\Property(property="Note", type="string", example="New shipment received", description="Any additional notes regarding the inventory."),
 *             @OA\Property(property="TrackingType", type="string", example="Lot Number", description="The type of tracking used for the part.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Inventory Created Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Inventory Created Successfully!", description="Success message indicating the inventory was created."),

 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Inventory has been updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Inventory has been updated", description="Success message indicating the inventory was updated."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="UOM not found", description="Error message indicating a validation issue.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Part Tracking not found", description="Error message indicating the requested resource was not found.")
 *         )
 *     )
 * )
 */
    public function store(StoreInventoryRequest $storeInventoryRequest): JsonResponse
    {
        $uom = UnitOfMeasure::where('name', $storeInventoryRequest->UOM)->first();
    
        if (!$uom) {
            return response()->json(['message' => 'UOM not found'], Response::HTTP_BAD_REQUEST);
        }
    
        $partTracking = PartTracking::where('name', $storeInventoryRequest->TrackingType)->first();
    
        if (!$partTracking) {
            return response()->json(['message' => 'Part Tracking not found'], Response::HTTP_BAD_REQUEST);
        }
    
        $part = Part::where('num', $storeInventoryRequest->PartNumber)->first();
    
        if (!$part) {
            $part = Part::create([
                'num' => $storeInventoryRequest->PartNumber,
                'activeFlag' => 1,
                'uomId' => $uom->id,
                'typeId' => $partTracking->typeId, 
            ]);
        } else {
            $part->update([
                'uomId' => $uom->id,
                'typeId' => $partTracking->typeId, 
            ]);
        }
    
        $location = Location::where('name', $storeInventoryRequest->Location)->firstOrFail();
    
        $existingInventory = Inventory::where('partId', $part->id)
            ->where('begLocationId', $location->id)
            ->first();
    
        if ($existingInventory) {
            $existingInventory->update([
                'changeQty' => $storeInventoryRequest->Qty,
                'qtyOnHand' => $existingInventory->qtyOnHand + $storeInventoryRequest->Qty,
                'dateCreated' => $storeInventoryRequest->Date,
                'cost' => $storeInventoryRequest->Cost,
                'partTrackingId' => $partTracking->id, 
                'typeId' => $partTracking->typeId, 
            ]);
    
            $partCost = PartCost::where('partId', $part->id)->first();
            $partCost->update([
                'avgCost' => $storeInventoryRequest->Cost,
                'dateLastModified' => now(),
                'qty' => $partCost->qty + $storeInventoryRequest->Qty,
                'totalCost' => $partCost->totalCost + ($storeInventoryRequest->Cost * $storeInventoryRequest->Qty),
            ]);
    
            $tag = Tag::where('partId', $part->id)
                ->where('locationId', $location->id)
                ->first();
    
            $tag->update([
                'qty' => $tag->qty + $storeInventoryRequest->Qty,
                'dateLastModified' => now(),
            ]);
    
            return response()->json(
                [
                    'message' => 'Inventory has been updated',
                    'inventory' => $existingInventory,
                ],
                Response::HTTP_OK
            );
        } else {
            $inventory = Inventory::create([
                'partId' => $part->id,
                'begLocationId' => $location->id,
                'endLocationId' => $location->id,
                'changeQty' => $storeInventoryRequest->Qty,
                'qtyOnHand' => $storeInventoryRequest->Qty,
                'dateCreated' => $storeInventoryRequest->Date,
                'partTrackingId' => $partTracking->id,
                'locationGroupId' => $location->locationGroupId, 
                'cost' => $storeInventoryRequest->Cost,
                'typeId' => $partTracking->typeId, 
            ]);
    
            PartCost::create([
                'avgCost' => $storeInventoryRequest->Cost,
                'dateCreated' => $storeInventoryRequest->Date,
                'dateLastModified' => now(),
                'qty' => $storeInventoryRequest->Qty,
                'totalCost' => $storeInventoryRequest->Cost * $storeInventoryRequest->Qty,
                'partId' => $part->id,
            ]);
    
            $tag = Tag::create([
                'dateCreated' => now(),
                'dateLastCycleCount' => now(),
                'dateLastModified' => now(),
                'num' => null,
                'qty' => $storeInventoryRequest->Qty,
                'qtyCommitted' => 0,
                'serializedFlag' => 0,
                'trackingEncoding' => null,
                'usedFlag' => 0,
                'woItemId' => null,
                'partId' => $part->id,
                'typeId' => 0,
                'locationId' => $location->id,
            ]);
    
            if ($storeInventoryRequest->TrackingType === 'Serial Number') {
                $lastSerialNum = SerialNum::where('partTrackingId', $inventory->partTrackingId)
                    ->orderBy('serialNum', 'desc')
                    ->first();
    
                $lastNum = $lastSerialNum ? (int)substr($lastSerialNum->serialNum, -5) : 0;
    
                for ($i = 1; $i <= $storeInventoryRequest->Qty; $i++) {
                    $nextNum = $lastNum + $i;
                    $serialNum = sprintf('TB-1000-%05d', $nextNum);
    
                    $serial = Serial::create([
                        'committedFlag' => 0,
                        'tagId' => $tag->id,
                    ]);
    
                    SerialNum::create([
                        'serialId' => $serial->id,
                        'serialNum' => $serialNum,
                        'partTrackingId' => $inventory->partTrackingId,
                    ]);
                }
            }
    
            return response()->json(
                [
                    'message' => 'Inventory Created Successfully!',
                    'inventory' => $inventory,
                    'input' => $storeInventoryRequest->validated(),
                ],
                Response::HTTP_CREATED
            );
        }
    }
}    