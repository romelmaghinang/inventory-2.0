<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class AddressController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
            // Process the request and create or find the necessary records
            $account_type = AccountType::firstOrCreate(['name' => $request->name]);
            $country = Country::firstOrCreate(['name' => $request->countryName]);
            $state = State::firstOrCreate(['name' => $request->stateName]);

            $addressData = Address::create(
                [
                    'account_type_id' => $account_type->id,
                    'country_id' => $country->id,
                    'state_id' => $state->id,
                ]
            );
            return response()->json($addressData);
        }
}
