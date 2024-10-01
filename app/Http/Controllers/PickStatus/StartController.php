<?php

namespace App\Http\Controllers\PickStatus;

use App\Http\Controllers\Controller;
use App\Models\Pick;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
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
            
            $salesOrder = SalesOrder::findOrFail($salesOrderItem->soId);

            $pick = Pick::where('num', $salesOrder->num)->firstOrFail();

            $pick->update([
                'statusId' => 20,
            ]);

            $salesOrder->update([
                'statusId' => 25,
            ]);

            $salesOrderItem->update([
                'statusId' => 20,
            ]);

            $pickItems[] = $pick;
        }

        return response()->json(
            [
                'message' => 'Pick Item Has Started',
                'pickItems' => $pickItems,
            ],
            JsonResponse::HTTP_OK
        );
    }
}
