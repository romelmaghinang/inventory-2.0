<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CarrierController extends Controller
{
    public function getCarrierId(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $description = $request->input('description');

        $carrier = new Carrier();
        $carrierId = $carrier->getCarrierId($name, $description);

        return response()->json(['carrierId' => $carrierId]);
    }
}

