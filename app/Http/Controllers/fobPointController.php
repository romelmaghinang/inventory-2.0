<?php

namespace App\Http\Controllers;

use App\Models\fobPoint;
use Illuminate\Http\Request;

class fobPointController extends Controller
{
    public function getFobPointId(Request $request)
    {
        $fobPointId = $request->input('fobPointId');
        $fobPoint = new FobPoint();
        return $fobPoint->getFobPointIdByName($fobPointId);
    }
}
