<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\StorePartRequest;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTracking;
use App\Models\PartTrackingType;
use App\Models\PartType;
use App\Models\PurchaseOrderItemType;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PartController extends Controller
{
     public function store(StorePartRequest $storePartRequest): JsonResponse
    {
        $uom = UnitOfMeasure::where('name', $storePartRequest->uom)->firstOrFail();
        $partType = PartType::where('name', $storePartRequest->partType)->firstOrFail();
        $poItemType = PurchaseOrderItemType::where('name', $storePartRequest->poItemType)->firstOrFail();

        $part = Part::create(
            $storePartRequest->only(
                [
                    'partDetails',
                    'upc',
                    'weight',
                    'width',
                    'consumptionRate',
                    'revision',
                    'length'
                ]
            ) +
                [
                    'num' => $storePartRequest->partNumber,
                    'description' => $storePartRequest->partDescription,
                    'uomId' => $uom->id,
                    'typeId' => $partType->id,
                    'activeFlag' => $storePartRequest->active,
                    'weightUomId' => $storePartRequest->weightUom,
                    'sizeUomId' => $storePartRequest->sizeUom,
                    'url' => $storePartRequest->pictureUrl,
                    'defaultPoItemTypeId' => $poItemType->id,
                ]
        );

        $partTracking = PartTracking::where('name', $storePartRequest->primaryTracking)->firstOrFail();

        $partToTracking = PartToTracking::create(
            $storePartRequest->only('nextValue') +
                [
                    'partTrackingId' => $partTracking->id,
                    'partId' => $part->id,
                ]
        );

        if ($storePartRequest->primaryTracking === 'Serial Number') {
            $serialNumber = SerialNumber::createUniqueSerialNumber($partToTracking->partTrackingId);
        }

        return response()->json(
            [
                'message' => 'Part Created Successfully!',
                'partData' => $part,
                'partTrackingData' => $partTracking,
                'partToTrackingData' => $partToTracking,
                'serialNum' => $serialNumber ?? null,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part): JsonResponse
    {
        return response()->json($part, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartRequest $updatePartRequest, Part $part): JsonResponse
    {
        $uom = UnitOfMeasure::where('name', $updatePartRequest->uom)->firstOrFail();
        $partType = PartType::where('name', $updatePartRequest->partType)->firstOrFail();
        $poItemType = PurchaseOrderItemType::where('name', $updatePartRequest->poItemType)->firstOrFail();

        $partTrackingType = PartTrackingType::where('name', $updatePartRequest->tracks)->firstOrFail();

        $part->update(
            $updatePartRequest->only(
                [
                    'partDetails',
                    'upc',
                    'weight',
                    'width',
                    'consumptionRate',
                    'revision',
                    'length'
                ]
            ) +
                [
                    'num' => $updatePartRequest->partNumber,
                    'description' => $updatePartRequest->partDescription,
                    'uomId' => $uom->id,
                    'typeId' => $partType->id,
                    'activeFlag' => $updatePartRequest->active,
                    'weightUomId' => $updatePartRequest->weightUom,
                    'sizeUomId' => $updatePartRequest->sizeUom,
                    'url' => $updatePartRequest->pictureUrl,
                    'defaultPoItemTypeId' => $poItemType->id,
                ]
        );

        $partTracking = PartTracking::updateOrCreate(
            ['part_id' => $part->id],
            $updatePartRequest->only('description') +
                [
                    'name' => $updatePartRequest->primaryTracking,
                    'typeId' => $partTrackingType->id,
                    'abbr' => $updatePartRequest->uom,
                ]
        );

        $partToTracking = PartToTracking::updateOrCreate(
            ['partTrackingId' => $partTracking->id, 'partId' => $part->id],
            $updatePartRequest->only('nextValue')
        );

        return response()->json(
            [
                'message' => 'Product Updated Successfully!',
                'partData' => $part,
                'partTrackingData' => $partTracking,
                'partToTrackingData' => $partToTracking,
            ],
            Response::HTTP_OK
        );
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part): JsonResponse
    {
        $part->delete();

        return response()->json(
            [
                'message' => 'Part Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
