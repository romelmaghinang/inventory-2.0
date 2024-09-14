<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Http\Requests\Pick\UpdatePickRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTracking;
use App\Models\PartTrackingType;
use App\Models\Pick;
use App\Models\PickItem;
use App\Models\Product;
use App\Models\SerialNumber;
use App\Models\TableReference;
use App\Models\TrackingInfo;
use App\Rules\PartTrackingTypeRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class PickController extends Controller
{
    public function store(StorePickRequest $storePickRequest): JsonResponse
    {
        foreach ($storePickRequest->all() as $item) {
            $part = Part::where('num', $item['partNum'])->firstOrFail();

            $partTracking = PartTracking::where('name', $item['partTrackingType'])->firstOrFail();

            try {
                $partToTracking = PartToTracking::where('partId', $part->id)
                    ->where('partTrackingId', $partTracking->id)
                    ->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'Part tracking not found for the given part and tracking ID.',
                ], Response::HTTP_NOT_FOUND);
            }

            $location = Location::where('name', $item['locationName'])->firstOrFail();

            if ($item['partTrackingType'] === 'Serial Number') {
                $serialNumber = SerialNumber::createUniqueSerialNumber($partToTracking->partTrackingId);

                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId
                    ]
                );
            }

            if ($item['partTrackingType'] === 'Expiration Date') {
                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId,
                        'infoDate' => $item['trackingInfo'],
                    ]
                );
            }

            if ($item['partTrackingType'] === 'Revision Level' || $item['partTrackingType'] === 'Lot Number') {
                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId,
                        'info' => $item['trackingInfo'],
                    ]
                );
            }

            $pick = Pick::create(
                [
                    'num' => $item['pickNum'],
                    'locationGroupId' => $location->locationGroupId,
                ]
            );
        }

        return response()->json(
            [
                'message' => 'Pick Created Successfully!',
                'serialNum' => $serialNumber ?? null,
                'trackingInfo' => $trackingInfo ?? null,
                'pick' => $pick
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Pick $pick): JsonResponse
    {
        return response()->json($pick, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePickRequest $updatePickRequest, Pick $pick): JsonResponse
    {
        $location = Location::where('name', $updatePickRequest->locationName)->firstOrFail();

        $pick->update(
            $updatePickRequest->validated() +
                [
                    'num' => $updatePickRequest->pickNum,
                    'locationGroupId' => $location->locationGroupId,
                ]
        );

        return response()->json(
            [
                'message' => 'Pick Updated Successfully!',
                'pick' => $pick
            ],
            Response::HTTP_OK
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pick $pick): JsonResponse
    {
        $pick->delete();

        return response()->json(
            [
                'message' => 'Pick Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
