<?php

namespace App\Http\Controllers\PickStatus;

use App\Http\Controllers\Controller;
use App\Models\Pick;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\PickItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StartController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        $startItemRequest = Validator::make(
            $request->all(),
            [
                '*.soItemId' => ['required', 'numeric', 'exists:soitem,id']
            ]
        );

        if ($startItemRequest->fails()) {
            return response()->json(['errors' => $startItemRequest->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedItems = $startItemRequest->validated();
        $pickItems = [];

        foreach ($validatedItems as $item) {
            $salesOrderItem = SalesOrderItems::findOrFail($item['soItemId']);
            $salesOrder = SalesOrder::findOrFail($salesOrderItem->soId);
            $pick = Pick::where('num', $salesOrder->num)->firstOrFail();

            $pick->update(['statusId' => 20]);
            $salesOrder->update(['statusId' => 25]);
            $salesOrderItem->update(['statusId' => 20]);

            $product = Product::findOrFail($salesOrderItem->productId);

            $pickItem = PickItem::create([
                'qty' => $salesOrderItem->qtyOrdered,
                'partId' => $product->partId,
                'pickId' => $pick->id,
                'soItemId' => $salesOrderItem->id,
                'statusId' => 20,
                'uomId' => $salesOrderItem->uomId,
            ]);

            $pickItems[] = $pickItem;
        }

        return response()->json(
            [
                'message' => 'Pick Item has started',
                'pickItems' => $pickItems,
            ],
            JsonResponse::HTTP_OK
        );
    }
}
