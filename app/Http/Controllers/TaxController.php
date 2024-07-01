<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaxController extends Controller
{
    public function getTaxRateData(Request $request): JsonResponse
    {
        $taxRateName = $request->input('taxRateName');

        $tax = new Tax();
        $taxRateDetails = $tax->getTaxRateData($taxRateName);

        return response()->json($taxRateDetails);
    }
}
