<?php

namespace App\Http\Controllers;

use App\Models\CarrierService;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

class CarrierServiceController extends Controller
{
    public function getCarrierServiceId(Request $request): JsonResponse
    {
        $carrierName = $request->input('carrierId');
        $code = $request->input('code');
        $name = $request->input('name');

        $carrierService = new CarrierService();
        $carrierServiceId = $carrierService->getCarrierServiceId($carrierName, $code, $name);

        return response()->json(['carrierServiceId' => $carrierServiceId]);
    }
}
