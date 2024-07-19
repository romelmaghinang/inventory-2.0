<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\CarrierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $carrier = Carrier::firstOrCreate([
            'name' => $request->name,
            'description' => $request->description,
            'description' => $request->carrierDescription,
            'name' => $request->carrierName,
            'readOnly' => $request->readOnly,
            'scac' => $request->scas,
        ]);

        $carrierService = CarrierService::firstOrCreate([
            'carrierId' => $carrier->id,
            'name' => $request->name,
            'code' => $request->code,
            'code' => $request->code,
            'carrierId' => $carrier->id,
            'code' => $request->carrierCode,
            'name' => $request->carrierServiceName,
            'readOnly' => $request->readOnly,
        ]);

        return response()->json([
            'carrierId' => $carrier->id,
            'carrierServiceId' => $carrierService->id,
        ]);
    }
}
