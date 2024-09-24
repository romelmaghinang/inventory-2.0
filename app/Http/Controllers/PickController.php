<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Http\Requests\Pick\UpdatePickRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartTracking;
use App\Models\Pick;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PickController extends Controller
{
    public function store(StorePickRequest $storePickRequest): JsonResponse
    {
        $part = Part::where('num', $storePickRequest->partNum)->firstOrFail();
        $partTracking = PartTracking::where('name', $storePickRequest->partTrackingType)->firstOrFail();
    
        if ($storePickRequest->partTrackingType === 'Serial Number') {
            $trackingInfos = [];
    
            foreach ($storePickRequest->validated()['trackingInfo'] as $serialNumber) {
                try {
                    SerialNumber::where('serialNum', $serialNumber)
                        ->where('partTrackingId', $partTracking->id)
                        ->firstOrFail();
                    
                    $trackingInfos[] = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'info' => $serialNumber, 
                    ]);
                } catch (ModelNotFoundException $e) {
                    return response()->json(
                        [
                            'message' => "Serial Number $serialNumber does not exist.",
                        ],
                        Response::HTTP_NOT_FOUND
                    );
                }
            }
        } else {
            switch ($storePickRequest->partTrackingType) {
                case 'Expiration Date':
                    $trackingInfo = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'infoDate' => $storePickRequest->trackingInfo[0],
                    ]);
                    break;
    
                case 'Revision Level':
                case 'Lot Number':
                    $trackingInfo = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'info' => $storePickRequest->trackingInfo[0],
                    ]);
                    break;
    
                default:
                    return response()->json(
                        [
                            'message' => 'Invalid part tracking type.',
                        ],
                        Response::HTTP_BAD_REQUEST
                    );
            }
        }
    
        $location = Location::where('name', $storePickRequest->locationName)->firstOrFail();
    
        $pick = Pick::create([
            'num' => $storePickRequest->pickNum,
            'locationGroupId' => $location->locationGroupId,
            'dateCreated' => Carbon::now(),
            'dateFinished' => Carbon::now(),
            'dateLastModified' => Carbon::now(),
            'dateScheduled' => Carbon::now(),
            'dateStarted' => Carbon::now(),
        ]);
    
        return response()->json(
            [
                'message' => 'Pick Created Successfully!',
                'trackingInfos' => $trackingInfos ?? [], 
                'pick' => $pick
            ],
            Response::HTTP_CREATED
        );
    }
}