<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryAndState\StoreCountryAndStateRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryAndStateController extends Controller
{
    public function store(StoreCountryAndStateRequest $storeCountryAndStateRequest): JsonResponse
    {
        $country = Country::create(['name' => $storeCountryAndStateRequest->countryName]);

        $state = State::create(['name' => $storeCountryAndStateRequest->stateName]);

        return response()->json(
            [
                'message' => 'Country And State Created Successfully!',
                'country' => $country,
                'state' => $state,
            ],
            Response::HTTP_CREATED
        );
    }
}
