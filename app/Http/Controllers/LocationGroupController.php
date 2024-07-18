<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\LocationGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationGroupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locationGroup = LocationGroup::firstOrCreate(['name' => $request->locationGroupName]);

        $location = Location::firstOrCreate(
            [
                'name' => $request->locationName
            ],
            [
                'locationGroupId' => $locationGroup->id,
                'activeFlag' => $request->activeFlag,
                'countedAsAvailable' => $request->countedAsAvailable,
                'defaultFlag' => $request->defaultFlag,
                'pickable' => $request->pickable,
                'receivable' => $request->receivable,
                'sortOrder' => $request->sortOrder,
                'typeId' => $request->typeId,
            ]
        );

        return response()->json($location);
    }
}
