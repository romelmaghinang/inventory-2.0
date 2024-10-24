<?php

namespace App\Http\Controllers\ShipStatus;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        $packRequest = Validator::make(
            $request->all(),
            [
                'shipId' => ['required', 'numeric', 'exists:ship,id']
            ]
        );

        $ship = Ship::findOrFail($packRequest->validated()['shipId']);

        $ship->update(
            [
                'statusId' => 20
            ]
        );

        return response()->json(
            [
                'message' => 'Packed'
            ]
        );
    }
}
