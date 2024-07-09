<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CarrierController extends Controller
{
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

