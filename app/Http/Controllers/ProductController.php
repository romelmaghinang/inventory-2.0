<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Part;
use App\Models\Product;
use App\Models\SalesOrderItemType;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
 * @OA\Post(
 *     path="api/product",
 *     summary="Create a new Product",
 *     description="Creates a new product based on the provided details.",
 *     tags={"Product"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="partNumber", type="string", description="Part number of the product"),
 *             @OA\Property(property="productNumber", type="string", description="Unique product number"),
 *             @OA\Property(property="productDescription", type="string", description="Description of the product"),
 *             @OA\Property(property="productDetails", type="string", description="Detailed information about the product"),
 *             @OA\Property(property="uom", type="string", description="Unit of Measure (e.g., Kilogram)"),
 *             @OA\Property(property="price", type="number", format="float", description="Price of the product"),
 *             @OA\Property(property="class", type="string", description="Class or category of the product"),
 *             @OA\Property(property="active", type="boolean", description="Whether the product is active"),
 *             @OA\Property(property="taxable", type="boolean", description="Whether the product is taxable"),
 *             @OA\Property(property="combo", type="boolean", description="Whether the product is a combo product"),
 *             @OA\Property(property="allowUom", type="boolean", description="Whether sellable in other UOMs"),
 *             @OA\Property(property="productUrl", type="string", description="URL of the product"),
 *             @OA\Property(property="productPictureUrl", type="string", description="Picture URL of the product"),
 *             @OA\Property(property="productUpc", type="string", description="UPC code of the product"),
 *             @OA\Property(property="productSku", type="string", description="SKU of the product"),
 *             @OA\Property(property="productSoItemType", type="string", description="Sales Order Item Type of the product"),
 *             @OA\Property(property="incomeAccount", type="string", description="Income account associated with the product"),
 *             @OA\Property(property="weight", type="number", format="float", description="Weight of the product"),
 *             @OA\Property(property="weightUom", type="string", description="Unit of Measure for the weight"),
 *             @OA\Property(property="width", type="number", format="float", description="Width of the product"),
 *             @OA\Property(property="height", type="number", format="float", description="Height of the product"),
 *             @OA\Property(property="length", type="number", format="float", description="Length of the product"),
 *             @OA\Property(property="sizeUom", type="string", description="Unit of Measure for size (e.g., cm)"),
 *             @OA\Property(property="default", type="boolean", description="Whether this is the default product configuration"),
 *             @OA\Property(property="alertNote", type="string", description="Any alert notes related to the product"),
 *             @OA\Property(property="cartonCount", type="integer", description="Number of cartons for packaging"),
 *             @OA\Property(property="cartonType", type="string", description="Type of carton used for packaging"),
 *             @OA\Property(property="cf", type="string", description="Custom field value"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message"),
 *             @OA\Property(property="product", type="object", description="The newly created product")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Validation error message")
 *         )
 *     )
 * )
 */
    public function store(StoreProductRequest $storeProductRequest): JsonResponse
    {
        $part = Part::where('num', $storeProductRequest->partNumber)->firstOrFail();
        $soItem = SalesOrderItemType::where('name', $storeProductRequest->productSoItemType)->firstOrFail();
        $uom = UnitOfMeasure::where('name', $storeProductRequest->uom)->firstOrFail();

        $product = Product::create(
            $storeProductRequest->only(
                [
                    'price',
                    'weight',
                    'width',
                    'height',
                    'length',
                    'alertNote',
                    'cf'
                ]
            ) +
                [
                    'partId' => $part->id,
                    'num' => $storeProductRequest->productNumber,
                    'description' => $storeProductRequest->productDescription,
                    'details' => $storeProductRequest->productDetails,
                    'activeFlag' => $storeProductRequest->active,
                    'taxableFlag' => $storeProductRequest->taxable,
                    'showSoComboFlag' => $storeProductRequest->combo,
                    'sellableInOtherUoms' => $storeProductRequest->allowUom,
                    'url' => $storeProductRequest->productUrl,
                    'upc' => $storeProductRequest->productUpc,
                    'sku' => $storeProductRequest->productSku,
                    'defaultSoItemType' => $soItem->id,
                    'uomId' => $uom->id,
                ]
        );

        return response()->json(
            [
                'message' => 'Product Create Successfully!',
                'product' => $product,
            ],
            Response::HTTP_CREATED
        );
    }

/**
 * @OA\Get(
 *     path="/api/product",
 *     summary="Get Products",
 *     description="Retrieves products. If num is provided as a query parameter or in the request body, returns that product; otherwise, returns all products.",
 *     tags={"Product"},
 *     @OA\Parameter(
 *         name="num",
 *         in="query",
 *         description="Number of the product to retrieve",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="num", type="string", description="Number of the product to retrieve")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message"),
 *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     )
 * )
 */

 public function show(Request $request): JsonResponse
 {
     $numFromQuery = $request->input('num');
     $numFromBody = $request->input('num'); 
 
     if ($numFromQuery || $numFromBody) {
         $num = $numFromQuery ?? $numFromBody;
 
         $request->validate([
             'num' => 'required|string|exists:product,num',
         ]);
 
         $product = Product::where('num', $num)->first();
 
         if (!$product) {
             return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
         }
 
         return response()->json([
             'message' => 'Product retrieved successfully',
             'data' => [$product], 
         ], Response::HTTP_OK);
     }
 
     $products = Product::all();
 
     return response()->json([
         'message' => 'Products retrieved successfully',
         'data' => $products,
     ], Response::HTTP_OK);
 }
 
    /**
     * @OA\Put(
     *     path="api/product",
     *     summary="Update a Product",
     *     description="Updates an existing product based on the provided productId in the request body.",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="productId", type="integer", description="ID of the product to update"),
     *             @OA\Property(property="partNumber", type="string", description="Part number of the product"),
     *             @OA\Property(property="productNumber", type="string", description="Unique product number"),
     *             @OA\Property(property="productDescription", type="string", description="Description of the product"),
     *             @OA\Property(property="productDetails", type="string", description="Detailed information about the product"),
     *             @OA\Property(property="uom", type="string", description="Unit of Measure (e.g., Kilogram)"),
     *             @OA\Property(property="price", type="number", format="float", description="Price of the product"),
     *             @OA\Property(property="class", type="string", description="Class or category of the product"),
     *             @OA\Property(property="active", type="boolean", description="Whether the product is active"),
     *             @OA\Property(property="taxable", type="boolean", description="Whether the product is taxable"),
     *             @OA\Property(property="combo", type="boolean", description="Whether the product is a combo product"),
     *             @OA\Property(property="allowUom", type="boolean", description="Whether sellable in other UOMs"),
     *             @OA\Property(property="productUrl", type="string", description="URL of the product"),
     *             @OA\Property(property="productPictureUrl", type="string", description="Picture URL of the product"),
     *             @OA\Property(property="productUpc", type="string", description="UPC code of the product"),
     *             @OA\Property(property="productSku", type="string", description="SKU of the product"),
     *             @OA\Property(property="productSoItemType", type="string", description="Sales Order Item Type of the product"),
     *             @OA\Property(property="incomeAccount", type="string", description="Income account associated with the product"),
     *             @OA\Property(property="weight", type="number", format="float", description="Weight of the product"),
     *             @OA\Property(property="weightUom", type="string", description="Unit of Measure for the weight"),
     *             @OA\Property(property="width", type="number", format="float", description="Width of the product"),
     *             @OA\Property(property="height", type="number", format="float", description="Height of the product"),
     *             @OA\Property(property="length", type="number", format="float", description="Length of the product"),
     *             @OA\Property(property="sizeUom", type="string", description="Unit of Measure for size (e.g., cm)"),
     *             @OA\Property(property="default", type="boolean", description="Whether this is the default product configuration"),
     *             @OA\Property(property="alertNote", type="string", description="Any alert notes related to the product"),
     *             @OA\Property(property="cartonCount", type="integer", description="Number of cartons for packaging"),
     *             @OA\Property(property="cartonType", type="string", description="Type of carton used for packaging"),
     *             @OA\Property(property="cf", type="string", description="Custom field value")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message"),
     *             @OA\Property(property="product", type="object", description="Updated product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message")
     *         )
     *     )
     * )
     */
    public function update(UpdateProductRequest $updateProductRequest, $id): JsonResponse
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    
        $product->update($updateProductRequest->validated());
    
        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ], Response::HTTP_OK);
    }
    



    public function destroy(Request $request): JsonResponse
    {
        $productId = $request->input('productId');
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
