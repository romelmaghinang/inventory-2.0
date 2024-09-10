<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Http\Requests\Pick\UpdatePickRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTrackingType;
use App\Models\Pick;
use App\Models\PickItem;
use App\Models\Product;
use App\Models\SerialNumber;
use App\Models\TableReference;
use App\Models\TrackingInfo;
use App\Rules\PartTrackingTypeRule;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class PickController extends Controller
{
    public function store(StorePickRequest $storePickRequest): JsonResponse
    {
        foreach ($storePickRequest->all() as $item) {
            $part = Part::where('num', $item['partNum'])->firstOrFail();

            $partToTracking = PartToTracking::where('partId', $part->id)->firstOrFail();

            $partTrackingType = PartTrackingType::where('name', $item['partTrackingType'])->firstOrFail();

            if ($partToTracking->partTracking->typeId !== $partTrackingType->id)
            {
                return response()->json(
                    [
                        'message' => 'Part Tracking and Part Tracking Type is not the same'
                    ]
                );
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

        $pick->update(
            $updatePickRequest->only([
                'dateCreated',
                'dateFinished',
                'dateLastModified',
                'dateScheduled',
                'dateStarted',
                'locationGroupId',
                'num',
                'priority',
                'userId',
            ]) +
                [
                    'statusId' => $updatePickRequest->pickStatusId,
                    'typeId' => $updatePickRequest->pickTypeId,
                ]
        );

        foreach ($updatePickRequest->items as $item) {
            $pickItem = PickItem::find($item['id']);

            if ($pickItem) {
                $pickItem->update(
                    array_merge(
                        $item,
                        [
                            'statusId' => $item['pickItemStatusId'],
                            'typeId' => $item['pickItemTypeId'],
                            'pickId' => $pick->id,
                        ]
                    )
                );
            } else {
                PickItem::create(
                    array_merge(
                        $item,
                        [
                            'statusId' => $item['pickItemStatusId'],
                            'typeId' => $item['pickItemTypeId'],
                            'pickId' => $pick->id,
                        ]
                    )
                );
            }
        }

        foreach ($updatePickRequest->items as $item) {
            $part = Part::find($item['partId']);
            $tableReference = TableReference::find($updatePickRequest->recordId);

            if ($part && $part->trackingFlag == true) {
                $inventoryLog = InventoryLog::where('partId', $part->id)->firstOrFail();

                $trackingInfo = TrackingInfo::updateOrCreate(
                    [
                        'partTrackingId' => $inventoryLog->partTrackingId,
                    ],
                    $updatePickRequest->only(
                        [
                            'info',
                            'infoDate',
                            'infoDouble',
                            'infoInteger',
                            'qty',
                            'recordId',
                        ]
                    ) +
                        [
                            'tableId' => $tableReference->tableId,
                        ]
                );
            }
        }

        return response()->json(
            [
                'message' => 'Pick Updated Successfully!',
                'pickData' => $pick,
                'trackingInfo' => isset($trackingInfo) ? $trackingInfo : null
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
