<?php

namespace App\Http\Controllers\ReceiptItemStatus;

use App\Http\Controllers\Controller;
use App\Models\ReceiptItem;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReconciledController extends Controller
{
    /**
     * Handle the incoming request.
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
