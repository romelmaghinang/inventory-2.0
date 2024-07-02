<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class AddressController extends Controller
{
    public function getOrCreateAddress(Request $request): JsonResponse
    {
        $accountName = $request->input('accountName');
        $countryName = $request->input('countryName');
        $stateName = $request->input('stateName');

        $address = new Address();
        $addressDetails = $address->getOrCreateAddress($accountName, $countryName, $stateName);

        return response()->json($addressDetails);
    }
}
