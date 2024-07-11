<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipToAddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $country = Country::firstOrCreate(['name' => $request->shipToCountryName]);
        $state = State::firstOrCreate(['name' => $request->shipToStateName]);

        $addressData = Address::firstOrCreate(
            [
                'accountId' => $request->accountId,
                'name' => $request->shipToName,
                'city' => $request->shipToCity,
                'countryId' => $country->id,
                'defaultFlag' => $request->defaultFlag,
                'locationGroupId' => $request->locationGroupId,
                'addressName' => $request->shipToAddress,
                'stateId' => $state->id,
                'address' => $request->shipToAddress,
                'typeId' => $request->accountId,
                'zip' => $request->shipToZip,
            ]
        );

        return response()->json($addressData);
    }
}
