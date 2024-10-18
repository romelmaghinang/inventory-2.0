<?php

namespace App\Http\Controllers\ReceiptStatus;

use App\Http\Controllers\Controller;
use App\Models\ReceiptItem;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FulfilledController extends Controller
{
    /**
     * Handle the incoming request.
     */
    /**
 * @OA\Post(
 *     path="/api/receipt-fulfilled",
 *     tags={"Receipt"},
 *     summary="Fulfill a receipt item",
 *     description="Marks a receipt item as fulfilled by updating its status.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="receiptItemId", type="integer", example=1, description="ID of the receipt item to be fulfilled")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receipt Item fulfilled",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Receipt Item fulfilled"),
 *             @OA\Property(
 *                 property="receiptItem",
 *                 type="object",
 *                 description="Details of the fulfilled receipt item",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="statusId", type="integer", example=40),
 *                 @OA\Property(property="dateLastModified", type="string", example="2024-10-18 15:30:00")
 *             ),
 *             @OA\Property(
 *                 property="receipt",
 *                 type="object",
 *                 description="Details of the related receipt",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="statusId", type="integer", example=40)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(
 *             @OA\Property(property="errors", type="object", description="Validation errors")
 *         )
 *     )
 * )
 */
    public function __invoke(Request $request): JsonResponse
    {
        $fulfillRequest = Validator::make(
            $request->all(),
            [
                'receiptItemId' => ['required', 'numeric', 'exists:receiptitem,id']
            ]
        );

        if ($fulfillRequest->fails()) {
            return response()->json(['errors' => $fulfillRequest->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $fulfillRequest->validated();

        $receiptItem = ReceiptItem::findOrFail($validatedData['receiptItemId']);
        $receipt = Receipt::findOrFail($receiptItem->receiptId);

        $receiptItem->update(['statusId' => 40]);
        $receiptItem->update([ 'dateLastModified' => Carbon::now(),]);
        $receipt->update(['statusId' => 40]);
               

        return response()->json(
            [
                'message' => 'Receipt Item fulfilled',
                'receiptItem' => $receiptItem,
                'receipt' => $receipt,
            ],
            JsonResponse::HTTP_OK
        );
    }
}
