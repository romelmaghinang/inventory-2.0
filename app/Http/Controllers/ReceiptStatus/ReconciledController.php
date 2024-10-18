<?php

namespace App\Http\Controllers\ReceiptStatus;

use App\Http\Controllers\Controller;
use App\Models\ReceiptItem;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReconciledController extends Controller
{
    /**
     * Handle the incoming request.
     */
    /**
 * @OA\Post(
 *     path="/api/receipt-reconciled",
 *     tags={"Receipt"},
 *     summary="Reconcile a receipt item",
 *     description="Reconciles a receipt item by updating its status and reconciliation date.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="receiptItemId", type="integer", example=1, description="ID of the receipt item to be reconciled")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receipt Item reconciled",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Receipt Item reconciled"),
 *             @OA\Property(
 *                 property="receiptItem",
 *                 type="object",
 *                 description="Details of the reconciled receipt item",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="statusId", type="integer", example=20),
 *                 @OA\Property(property="dateReconciled", type="string", example="2024-10-18 15:30:00"),
 *                 @OA\Property(property="dateLastModified", type="string", example="2024-10-18 15:30:00")
 *             ),
 *             @OA\Property(
 *                 property="receipt",
 *                 type="object",
 *                 description="Details of the related receipt",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="statusId", type="integer", example=20)
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
        $reconcileRequest = Validator::make(
            $request->all(),
            [
                'receiptItemId' => ['required', 'numeric', 'exists:receiptitem,id']
            ]
        );

        if ($reconcileRequest->fails()) {
            return response()->json(['errors' => $reconcileRequest->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $reconcileRequest->validated();

        $receiptItem = ReceiptItem::findOrFail($validatedData['receiptItemId']);
        $receipt = Receipt::findOrFail($receiptItem->receiptId);

        $receiptItem->update(['statusId' => 20]);
        $receiptItem->update([ 'dateReconciled' => Carbon::now(),]);
        $receiptItem->update([ 'dateLastModified' => Carbon::now(),]);

        $receipt->update(['statusId' => 20]);

        return response()->json(
            [
                'message' => 'Receipt Item reconciled',
                'receiptItem' => $receiptItem,
                'receipt' => $receipt,
            ],
            JsonResponse::HTTP_OK
        );
    }
}
