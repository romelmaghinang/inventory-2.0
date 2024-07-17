<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(StoreProductRequest $storeProductRequest): JsonResponse
    {
        $validateProduct = Product::create($storeProductRequest->validated());

        $product = Product::firstOrCreate(
            [
                'defaultSoItemType' => $validateProduct->defaultSoItemType,
                'details' => $validateProduct->details,
            ]
        );

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $updateProductRequest, Product $product): JsonResponse
    {
        $product->update($updateProductRequest->validated());

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            "message" => "success",
        ]);
    }
}
