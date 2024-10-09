<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryRequest;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartCost;
use App\Models\PartTracking;
use App\Models\PartTrackingType;
use App\Models\Tag; 
use App\Models\Serial; 
use App\Models\SerialNum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    public function store(StoreInventoryRequest $storeInventoryRequest): JsonResponse
    {
        $part = Part::where('num', $storeInventoryRequest->PartNumber)->firstOrFail();
        $location = Location::where('name', $storeInventoryRequest->Location)->firstOrFail();
    
        $existingInventory = Inventory::where('partId', $part->id)
            ->where('locationGroupId', $location->locationGroupId)
            ->first();
    
        if ($existingInventory) {
            return response()->json(
                [
                    'message' => 'Inventory already exists',
                ],
                Response::HTTP_CONFLICT
            );
        }
    
        $trackingType = PartTrackingType::where('name', $storeInventoryRequest->TrackingType)->first();
        $trackingTypeId = $trackingType ? $trackingType->id : null;
    
        $partTracking = PartTracking::where('name', $storeInventoryRequest->TrackingType)->firstOrFail();
    
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
            'typeId' => $trackingTypeId,
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
