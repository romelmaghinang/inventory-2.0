<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryLog\StoreInventoryLogRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTrackingType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    public function store(StoreInventoryLogRequest $storeInventoryLogRequest): JsonResponse
    {
        $part = Part::where('num', $storeInventoryLogRequest->PartNumber)->firstOrFail();
        $location = Location::where('name', $storeInventoryLogRequest->Location)->firstOrFail();
    
        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();
        $trackingType = PartTrackingType::where('name', $storeInventoryLogRequest->TrackingType)->first();
        $trackingTypeId = $trackingType ? $trackingType->id : null;

        $inventory = InventoryLog::create([
            'partId' => $part->id,
            'begLocationId' => $location->id,
            'endLocationId' => $location->id,
            'changeQty' => $storeInventoryLogRequest->Qty,
            'qtyOnHand' => $storeInventoryLogRequest->Qty,
            'dateCreated' => $storeInventoryLogRequest->Date,
            'partTrackingId' => $partToTracking->partTrackingId,
            'locationGroupId' => $location->locationGroupId,
            'cost' => $storeInventoryLogRequest->Cost,
            'trackingTypeId' => $trackingTypeId,
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
