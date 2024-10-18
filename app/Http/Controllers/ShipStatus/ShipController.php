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
/**
 * @OA\Post(
 *     path="/api/ship",
 *     tags={"Ship"},
 *     summary="Update the status of a shipment",
 *     description="Marks a shipment as shipped and updates the related sales order and sales order items statuses.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="shipId", type="integer", example=1, description="The ID of the shipment to update"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Shipment status updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Shipped")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Resource not found")
 *         )
 *     ),
 * )
 */
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
