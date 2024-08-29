<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Customer;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    public function store(StoreLocationRequest $storeLocationRequest): JsonResponse
    {
        $locationType = LocationType::where('name', $storeLocationRequest->type)->firstOrFail();
        $locationGroup = LocationGroup::firstOrCreate(['name' => $storeLocationRequest->locationGroup]);
        $customer = Customer::where('name', $storeLocationRequest->customerName)->firstOrFail();

        $location = Location::create(
            $storeLocationRequest->only(
                [
                    'description',
                    'pickable',
                    'receivable',
                    'sortOrder'
                ]
            ) +
                [
                    'name' => $storeLocationRequest->location,
                    'typeId' => $locationType->id,
                    'locationGroupId' => $locationGroup->id,
                    'defaultCustomerId' => $customer->id,
                    'activeFlag' => $storeLocationRequest->active,
                    'countedAsAvailable' => $storeLocationRequest->available,
                ]
        );

        return response()->json(
            [
                'message' => 'Location Created Successfully!',
                'location' => $location,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location): JsonResponse
    {
        return response()->json($location, Response::HTTP_OK);
    }


    public function destroy(Location $location): JsonResponse
    {
        $location->delete();

        return response()->json(
            [
                'message' => 'Location Created Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
