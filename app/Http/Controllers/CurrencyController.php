<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class CurrencyController extends Controller
{
    public function getCurrency(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $code = $request->input('code');
        $symbol = $request->input('symbol');
        $rate = $request->input('rate');

        $currency = new Currency();
        $currencyData = $currency->getCurrency($name, $code, $symbol, $rate);

        return response()->json($currencyData);
    }
}
