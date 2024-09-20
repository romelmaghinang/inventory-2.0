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
    public function store(StoreInventoryRequest $storeInventoryLogRequest): JsonResponse
    {
        $part = Part::where('num', $storeInventoryLogRequest->PartNumber)->firstOrFail();
        $location = Location::where('name', $storeInventoryLogRequest->Location)->firstOrFail();
    
        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();
        $trackingType = PartTrackingType::where('name', $storeInventoryLogRequest->TrackingType)->first();
        $trackingTypeId = $trackingType ? $trackingType->id : null;

        $inventory = Inventory::create([
            'partId' => $part->id,
            'begLocationId' => $location->id,
            'endLocationId' => $location->id,
            'changeQty' => $storeInventoryLogRequest->Qty,
            'qtyOnHand' => $storeInventoryLogRequest->Qty,
            'dateCreated' => $storeInventoryLogRequest->Date,
            'partTrackingId' => $partToTracking->partTrackingId,
            'locationGroupId' => $location->locationGroupId,
            'cost' => $storeInventoryLogRequest->Cost,
            'typeId' => $trackingTypeId,
        ]);

        PartCost::create([
            'avgCost' => $storeInventoryLogRequest->Cost, 
            'dateCreated' => $storeInventoryLogRequest->Date,
            'dateLastModified' => now(),
            'qty' => $storeInventoryLogRequest->Qty,
            'totalCost' => $storeInventoryLogRequest->Cost * $storeInventoryLogRequest->Qty,
            'partId' => $part->id,
        ]);
    
        return response()->json(
            [
                'message' => 'Inventory Created Successfully!',
                'inventory' => $inventory,
                'input' => $storeInventoryLogRequest->validated(),
            ],
            Response::HTTP_CREATED,
        );
    }
}