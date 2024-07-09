<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class AddressController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Log the incoming request data
            Log::info('AddressController invoked with data:', $request->all());

            // Process the request and create or find the necessary records
            $account_type = AccountType::firstOrCreate(['name' => $request->accountName]);
            $country = Country::firstOrCreate(['name' => $request->countryName]);
            $state = State::firstOrCreate(['name' => $request->stateName]);

            Address::create(
                [
                    'accountId' => $account_type->id,
                    'countryId' => $country->id,
                    'stateId' => $state->id,
                ]
            );

            // Log the IDs of the created or found records
            Log::info('Account, Country, and State IDs:', [
                'accountId' => $account_type->id,
                'countryId' => $country->id,
                'stateId' => $state->id
            ]);

            return response()->json([
                'accountId' => $account_type->id,
                'countryId' => $country->id,
                'stateId' => $state->id,
            ]);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error in AddressController:', ['message' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong in AddressController'], 500);
        }
    }
}
