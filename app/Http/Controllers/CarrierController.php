<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Support\Facades\Request;

class CarrierController extends Controller
{
    public function getCarrierId(Request $request)
    {
        $carrierId = $request->input('carrierId');
        $carrier = new Carrier();
        return $carrier->getCarrierIdByName($carrierId);
    }

    public function getCarrierServiceId(Request $request)
    {
        $carrierServiceId = $request->input('carrierServiceId');
        $carrier = new Carrier();
        return $carrier->getCarrierServiceIdByName($carrierServiceId);
    }
}

