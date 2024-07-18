<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $currency = Currency::firstOrCreate(
            [
                'activeFlag' => $request->activeFlag,
                'code' => $request->code,
                'excludeFromUpdate' => $request->excludeFromUpdate,
                'homeCurrency' => $request->homeCurrency,
                'lastChangedUserId' => auth('api')->id() ?: 1,
                'name' => $request->name,
                'rate' => $request->rate,
                'symbol' => $request->symbol,
            ]
        );

        return response()->json($currency);
    }
}
