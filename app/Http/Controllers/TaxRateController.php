<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $taxRateType = TaxRate::firstOrCreate(['name' => $request->taxRateName]);

        $taxRate = TaxRate::findOrCreate([
            'activeFlag' => $request->activeflag,
            'code' => $request->code,
            'defaultFlag' => $request->defaultFlag,
            'description' => $request->description,
            'orderTypeId' => $request->orderTypeId,
            'rate' => $request->rate,
            'taxAccountId' => $request->taxAccountId,
            'typeId' => $taxRateType
        ]);

        return response()->json($taxRate);
    }
}
