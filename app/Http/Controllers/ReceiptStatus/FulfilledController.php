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
