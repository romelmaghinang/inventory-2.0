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
        $taxData = TaxRate::firstOrCreate(['name' => $request->taxRateName]);

        return response()->json($taxData);
    }
}
