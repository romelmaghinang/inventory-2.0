<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductIfNotExistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $product = Product::firstOrCreate(
            [
                'defaultSoItemType' => $request->defaultSoItemType,
                'details' => $request->details,
            ]
        );

        return response()->json($product);
    }
}
