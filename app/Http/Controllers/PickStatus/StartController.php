<?php

namespace App\Http\Controllers\PickStatus;

use App\Http\Controllers\Controller;
use App\Models\PartToTracking;
use App\Models\Pick;
use App\Models\PickItem;
use App\Models\Product;
use App\Models\SalesOrderItems;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $startItemRequest = Validator::make(
            $request->all(),
            [
                '*.soItemId' => ['required', 'numeric', 'exists:soitem,id']
            ]
        );

        $pickItems = [];

        foreach ($startItemRequest->validated() as $item) {
            $salesOrderItem = SalesOrderItems::findOrFail($item['soItemId']);

            $pick = Pick::where('num', $salesOrderItem->salesOrder->num)->firstOrFail();

            $product = Product::findOrFail($salesOrderItem->productId);

            $partToTracking = PartToTracking::where('partId', $product->partId)->firstOrFail();

            if ($partToTracking->partTracking->name === 'Serial Number') {
                $serialNumber = SerialNumber::createUniqueSerialNumber($partToTracking->partTrackingId);

                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId
                    ]
                );
            }

            if ($partToTracking->partTracking->name === 'Expiration Date') {
                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId,
                    ]
                );
            }

            if ($partToTracking->partTracking->name === 'Revision Level' || $partToTracking->partTracking->name === 'Lot Number') {
                $trackingInfo = TrackingInfo::create(
                    [
                        'partTrackingId' => $partToTracking->partTrackingId,
                    ]
                );
            }

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
        }

        return response()->json(
            [
                'message' => 'Pick Item Has Started',
                'pickItem' => $pickItems,
                'serialNum' => $serialNumber ?? null,
                'trackingInfo' => $trackingInfo ?? null,

            ]
        );
    }
}
