<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function getShippingData(Request $request)
    {
        $shipTermsId = $request->input('shipTermsId');
        $shipToCountryId = $request->input('shipToCountryId');
        $shipToStateId = $request->input('shipToStateId');

        $shipping = new Shipping();

        return [
            'shipTermsId' => $shipping->getShipTermsIdByName($shipTermsId),
            'shipToCountryId' => $shipping->getShipToCountryIdByName($shipToCountryId),
            'shipToStateId' => $shipping->getShipToStateIdByName($shipToStateId)
        ];
    }
}
