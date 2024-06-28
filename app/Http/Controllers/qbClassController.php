<?php

namespace App\Http\Controllers;

use App\Models\qbClass;
use Illuminate\Http\Request;

class qbClassController extends Controller
{
    public function getQbClassId(Request $request)
    {
        $qbClassId = $request->input('qbClassId');
        $qbClass = new qbClass();
        return $qbClass->getQbClassIdByName($qbClassId);
    }
}
