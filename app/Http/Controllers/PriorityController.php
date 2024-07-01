<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PriorityController extends Controller
{
    public function getPriorityId(Request $request): JsonResponse
    {
        $name = $request->input('name');

        $priority = new Priority();
        $priorityId = $priority->getPriorityIdByName($name);

        if ($priorityId) {
            return response()->json(['id' => $priorityId]);
        } else {
            return response()->json(['message' => 'Priority not found'], 404);
        }
    }
}
