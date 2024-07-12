<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // BILL TO
        $billToCountry = Country::firstOrCreate(['name' => $request->billToCountryName]);
        $billToState = State::firstOrCreate(['name' => $request->billToStateName]);

        Address::firstOrCreate(
            ['addressName' => $request->billToAddress],
            [
                'accountId' => $request->accountId,
                'name' => $request->billToName,
                'city' => $request->billToCity,
                'countryId' => $billToCountry->id,
                'defaultFlag' => $request->defaultFlag,
                'locationGroupId' => $request->locationGroupId,
                'stateId' => $billToState->id,
                'address' => $request->billToAddress,
                'typeId' => $request->accountId,
                'zip' => $request->billToZip,
            ]
        );

        // SHIP TO
        $shipToCountry = Country::firstOrCreate(['name' => $request->shipToCountryName]);
        $shipToState = State::firstOrCreate(['name' => $request->shipToStateName]);

        Address::firstOrCreate(
            ['addressName' => $request->shipToAddress],
            [
                'accountId' => $request->accountId,
                'name' => $request->shipToName,
                'city' => $request->shipToCity,
                'countryId' => $shipToCountry->id,
                'defaultFlag' => $request->defaultFlag,
                'locationGroupId' => $request->locationGroupId,
                'stateId' => $shipToState->id,
                'address' => $request->shipToAddress,
                'typeId' => $request->accountId,
                'zip' => $request->shipToZip,
            ]
        );

        return response()->json(
            [
                'billToCountryId' => $billToCountry->id,
                'billToStateId' => $billToState->id,
                'shipToCountryId' => $shipToCountry->id,
                'shipToStateId' => $shipToState->id,
            ]
        );
    }
}