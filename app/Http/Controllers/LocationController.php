<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocationGroupId(Request $request)
    {
        $locationGroupId = $request->input('locationGroupId');
        $location = new Location();
        return $location->getLocationGroupIdByName($locationGroupId);
    }
}
