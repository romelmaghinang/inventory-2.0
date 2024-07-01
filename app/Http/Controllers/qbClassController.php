<?php

namespace App\Http\Controllers;

use App\Models\qbClass;
use Illuminate\Http\Request;

class qbClassController extends Controller
{
    public function getQbClassId(Request $request)
    {
        $name = $request->input('name');

        $qbClass = new QbClass();
        $qbClassId = $qbClass->getQbClassIdByName($name);

        if ($qbClassId) {
            return response()->json(['id' => $qbClassId], 200);
        } else {
            return response()->json(['message' => 'QB Class not found'], 404);
        }
    }
}
