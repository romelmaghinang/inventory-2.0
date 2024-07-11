<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __invoke(Request $request)
    {
        $taxData = Tax::firstOrCreate(['id' => $request->id ,'name' => $request->taxRateName]);

        return response()->json($taxData);
    }
}
