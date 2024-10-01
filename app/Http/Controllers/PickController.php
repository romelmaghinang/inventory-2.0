<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Http\Requests\Pick\UpdatePickRequest;
use App\Models\InventoryLog;
use App\Models\Location;
use App\Models\Part;
use App\Models\Pick;
use App\Models\PartTracking;
use App\Models\PickItem;
use App\Models\Product;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use App\Models\TableReference;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\TrackingInfoSn;
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

        $tableReference = TableReference::where('tableRefName', 'PickItem')->first();
        if (!$tableReference) {
            return response()->json(
                [
                    'message' => 'TableReference with PickItem not found.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        $tableId = $tableReference->tableId;

        $so = SalesOrder::where('num', $storePickRequest->pickNum)->first();
        if (!$so) {
            return response()->json(
                [
                    'message' => "Sales Order with pickNum {$storePickRequest->pickNum} not found.",
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $soItem = SalesOrderItems::where('soid', $so->id)->first();
        if (!$soItem) {
            return response()->json(
                [
                    'message' => "Sales Order Items for Sales Order ID {$so->id} not found.",
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $qty = $soItem->qtyOrdered;

        if ($storePickRequest->partTrackingType === 'Serial Number') {
            $trackingInfos = [];

            foreach ($storePickRequest->validated()['trackingInfo'] as $serialNumber) {
                try {
                    SerialNumber::where('serialNum', $serialNumber)
                        ->where('partTrackingId', $partTracking->id)
                        ->firstOrFail();

                    $trackingInfo = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'qty' => $qty,
                        'tableId' => $tableId,
                    ]);

                    TrackingInfoSn::create([
                        'partTrackingId' => $partTracking->id,
                        'serialNum' => $serialNumber,
                        'trackingInfoId' => $trackingInfo->id,
                    ]);

                    $trackingInfos[] = $trackingInfo;
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
                        'qty' => $qty,
                        'tableId' => $tableId,
                    ]);
                    break;

                case 'Revision Level':
                case 'Lot Number':
                    $trackingInfo = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'info' => $storePickRequest->trackingInfo[0],
                        'qty' => $qty,
                        'tableId' => $tableId,
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

        /* 

        $pick = Pick::create([
            'num' => $storePickRequest->pickNum,
            'locationGroupId' => $location->locationGroupId,
            'dateCreated' => Carbon::now(),
            'dateFinished' => Carbon::now(),
            'dateLastModified' => Carbon::now(),
            'dateScheduled' => Carbon::now(),
            'dateStarted' => Carbon::now(),
        ]);
        */

        $salesOrderItem = $soItem; 

        $pick = Pick::where('num', $salesOrderItem->salesOrder->num)->firstOrFail();

        $product = Product::findOrFail($salesOrderItem->productId);

        $pickItem = PickItem::create(
            [
                'qty' => $salesOrderItem->qtyOrdered,
                'partId' => $product->partId,
                'pickId' => $pick->id,
                'soItemId' => $salesOrderItem->id,
                'statusId' => 20,
                'uomId' => $salesOrderItem->uomId,
            ]
        );

        $pickItems[] = $pickItem;
    

        $so->update(['statusId' => 25]);

        $soItem->update(['statusId' => 40]);

        return response()->json(
            [
                'message' => 'Picked Successfully!',
                'trackingInfos' => $trackingInfos ?? [],
                //'pick' => $pick,
            ],
            Response::HTTP_CREATED
        );
    }
}


