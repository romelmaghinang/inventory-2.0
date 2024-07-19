<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesOrderItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $salesOrderItem = SalesOrderItems::create([
            'productId' => $request->productId,
            'note' => $request->note,
            'soLineItem' => $request->soLineItem,
            'soId' => $request->soId,
            'statusId' => $request->statusId,
            'typeId' => $request->typeId,
        ]);

        return response()->json($salesOrderItem);
    }
}
