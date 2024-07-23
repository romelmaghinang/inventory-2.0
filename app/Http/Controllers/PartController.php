<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\StorePartRequest;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Models\Part;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PartController extends Controller
{
    public function store(StorePartRequest $storePartRequest): JsonResponse
    {
        $part = Part::create($storePartRequest->validated());

        $product = Product::create($storePartRequest->validated() + ['partId' => $part->id]);

        return response()->json(
            [
                'partData' => $part,
                'productData' => $product,
                'message' => 'Product Created Successfully!',
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part): JsonResponse
    {
        return response()->json($part, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartRequest $updatePartRequest, Part $part): JsonResponse
    {
        $part->update($updatePartRequest->validated());

        $product = Product::where('partId', $part->id)->firstOrFail();
        $product->update($updatePartRequest->validated() + ['partId'=> $part->id]);

        return response()->json(
            [
                'partData' => $part,
                'productData' => $product,
                'message' => 'Product Updated Successfully!',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part): JsonResponse
    {
        $part->delete();

        return response()->json(
            [
                'message' => 'Part Deleted Successfully!'
            ]
        );
    }
}
