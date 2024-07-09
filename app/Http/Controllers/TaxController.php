<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaxController extends Controller
{
    public function __invoke(Request $request)
    {
        $taxData = Tax::firstOrCreate(['name' => $request->taxRateName]);

        return response()->json($taxData);
    }
}
