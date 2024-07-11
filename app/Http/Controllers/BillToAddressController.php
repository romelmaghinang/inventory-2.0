<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillToAddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $country = Country::firstOrCreate(['name' => $request->billToCountryName]);
        $state = State::firstOrCreate(['name' => $request->billToStateName]);

        $addressData = Address::firstOrCreate(
            [
                'accountId' => 2,
                'name' => $request->billToName,
                'city' => $request->billToCity,
                'countryId' => $country->id,
                'defaultFlag' => $request->defaultFlag,
                'locationGroupId' => $request->locationGroupId,
                'addressName' => $request->billToAddress,
                'stateId' => $state->id,
                'address' => $request->billToAddress,
                'typeId' => $request->accountId,
                'zip' => $request->billToZip,
            ]
        );

        return response()->json($addressData);
    }
}
