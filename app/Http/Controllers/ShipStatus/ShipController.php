<?php

namespace App\Http\Controllers\ShipStatus;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use App\Models\SalesOrder; 
use App\Models\SalesOrderItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        $packRequest = Validator::make(
            $request->all(),
            [
                'shipId' => ['required', 'numeric', 'exists:ship,id']
            ]
        );

        $ship = Ship::findOrFail($packRequest->validated()['shipId']);

        $ship->update(
            [
                'statusId' => 30
            ]
        );

        $soId = $ship->soId;

        $so = SalesOrder::where('id', $soId)->first();

        if ($so) {
            $so->update(
                [
                    'statusId' => 60
                ]
            );
        }

        $soItem = SalesOrderItems::where('soId', $soId)->first();

        if ($soItem) {
            $soItem->update(
                [
                    'statusId' => 50
                ]
            );
        }

        return response()->json(
            [
                'message' => 'Shipped'
            ]
        );
    }
}
