<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryRequest;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartCost; 
use App\Models\PartToTracking;
use App\Models\PartTrackingType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    public function store(StoreInventoryRequest $storeInventoryRequest): JsonResponse
    {
        $part = Part::where('num', $storeInventoryRequest->PartNumber)->firstOrFail();
        $location = Location::where('name', $storeInventoryRequest->Location)->firstOrFail();
    
        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();
        $trackingType = PartTrackingType::where('name', $storeInventoryRequest->TrackingType)->first();
        $trackingTypeId = $trackingType ? $trackingType->id : null;

        $inventory = Inventory::create([
            'partId' => $part->id,
            'begLocationId' => $location->id,
            'endLocationId' => $location->id,
            'changeQty' => $storeInventoryRequest->Qty,
            'qtyOnHand' => $storeInventoryRequest->Qty,
            'dateCreated' => $storeInventoryRequest->Date,
            'partTrackingId' => $partToTracking->partTrackingId,
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
    
        return response()->json(
            [
                'message' => 'Inventory Created Successfully!',
                'inventory' => $inventory,
                'input' => $storeInventoryRequest ->validated(),
            ],
            Response::HTTP_CREATED,
        );
    }
}