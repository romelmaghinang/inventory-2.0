<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class LocationController extends Controller
{
    public function getLocationGroup(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $description = $request->input('description', '');
        $parentId = $request->input('parentId');
        $pickable = $request->input('pickable', 1);
        $receivable = $request->input('receivable', 1);

        $location = new Location();
        $locationData = $location->getLocationGroup($name, $description, $parentId, $pickable, $receivable);

        return response()->json($locationData);
    }
}
