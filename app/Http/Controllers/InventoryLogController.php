<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryLog\StoreInventoryLogRequest;
use App\Http\Requests\InventoryLog\UpdateInventoryLogRequest;
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

        $inventory = InventoryLog::create(
            $storeInventoryLogRequest->only(['cost']) + [
                'partId' => $part->id,
                'begLocationId' => $location->id,
                'endLocationId' => $location->id,
                'changeQty' => $storeInventoryLogRequest->qty,
                'qtyOnHand' => $storeInventoryLogRequest->qty,
                'dateCreated' => $storeInventoryLogRequest->date,
                'partTrackingId' => $partToTracking->partTrackingId,
                'locationGroupId' => $location->locationGroupId,
            ],
        );

        return response()->json(
            [
                'message' => 'Inventory Created Successfully!',
                'inventory' => $inventory,
            ],
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryLog $inventoryLog): JsonResponse
    {
        return response()->json($inventoryLog, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryLogRequest $updateInventoryLogRequest, InventoryLog $inventoryLog): JsonResponse
    {
        $part = Part::where('num', $updateInventoryLogRequest->partNumber)->firstOrFail();

        $location = Location::where('name', $updateInventoryLogRequest->location)->firstOrFail();

        $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();

        $updateData = $updateInventoryLogRequest->only(['cost']) + [
            'partId' => $part->id,
            'begLocationId' => $location->id,
            'endLocationId' => $location->id,
            'changeQty' => $updateInventoryLogRequest->qty,
            'qtyOnHand' => $updateInventoryLogRequest->qty,
            'dateCreated' => $updateInventoryLogRequest->date,
            'partTrackingId' => $partToTracking->partTrackingId,
            'locationGroupId' => $location->locationGroupId,
        ];

        $inventoryLog->update($updateData);

        $inventoryLog->refresh();

        return response()->json(
            [
                'message' => 'Inventory Updated Successfully!',
                'inventory' => $inventoryLog,
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryLog $inventoryLog): JsonResponse
    {
        $inventoryLog->delete();

        return response()->json(
            [
                'message' => 'Inventory Deleted Successfully!',
            ],
            Response::HTTP_OK,
        );
    }
}
