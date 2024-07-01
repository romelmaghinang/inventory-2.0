<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    public function getShippingData(Request $request): JsonResponse
    {
        $shipTermsId = $request->input('shipTermsId');
        $shipToCountryId = $request->input('shipToCountryId');
        $shipToStateId = $request->input('shipToStateId');

        $shipping = new Shipping();
        $shippingDetails = $shipping->getShippingData($shipTermsId, $shipToCountryId, $shipToStateId);

        return response()->json($shippingDetails);
    }
}
