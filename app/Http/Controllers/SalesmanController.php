<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SalesmanController extends Controller
{
    public function getSalesmanData(Request $request): JsonResponse
    {
        $salesmanId = $request->input('SalesmanId');
        $salesmanName = $request->input('salesman');
        $salesmanInitials = $request->input('salesmanInitials');

        $salesman = new Salesman();
        $salesmanDetails = $salesman->getSalesmanData($salesmanId, $salesmanName, $salesmanInitials);

        return response()->json($salesmanDetails);
    }


}
