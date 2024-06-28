<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getCurrencyId(Request $request)
    {
        $currencyId = $request->input('currencyId');
        $currency = new Currency();
        return $currency->getCurrencyIdByName($currencyId);
    }

    public function getCurrencyRate(Request $request)
    {
        $currencyRate = $request->input('currencyRate');
        $currency = new Currency();
        return $currency->getCurrencyRateByName($currencyRate);
    }
}
