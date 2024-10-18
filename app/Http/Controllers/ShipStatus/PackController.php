<?php

namespace App\Http\Controllers\ShipStatus;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    /**
 * @OA\Post(
 *     path="/api/pack",
 *     tags={"Pack"},
 *     summary="Update the status of a shipment to packed",
 *     description="Marks a shipment as packed based on the provided ship ID.",
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
 *             @OA\Property(property="message", type="string", example="Packed")
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
 *     )
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
                'statusId' => 20
            ]
        );

        return response()->json(
            [
                'message' => 'Packed'
            ]
        );
    }
}
