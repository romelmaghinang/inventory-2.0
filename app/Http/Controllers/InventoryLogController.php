<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryLog\StoreInventoryLogRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartToTracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryLogController extends Controller
{
    public function store(StoreInventoryLogRequest $storeInventoryLogRequest): JsonResponse
    {
        $part = Part::where('num', $storeInventoryLogRequest->partNumber)->firstOrFail();
        $location = Location::where('name', $storeInventoryLogRequest->location)->firstOrFail();

        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();

        $inventory = InventoryLog::create($storeInventoryLogRequest->only(
            [
                'cost'
            ]
        ) +
            [
                'partId' => $part->id,
                'begLocationId' => $location->id,
                'endLocationId' => $location->id,
                'changeQty' => $storeInventoryLogRequest->qty,
                'qtyOnHand' => $storeInventoryLogRequest->qty,
                'dateCreated' => $storeInventoryLogRequest->date,
                'partTrackingId' => $partToTracking->partTrackingId,
                'locationGroupId' => $location->locationGroupId,
            ]
        );

        return response()->json(
            [
                'message' => 'Inventory Created Successfully!',
                'invetory' => $inventory,
            ],
            Response::HTTP_CREATED
        );
    }
}
