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
        ]);

        $carrierService = CarrierService::firstOrCreate([
            'carrier_id' => $carrier->id,
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response()->json($carrierService);
    }
}
