<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\StorePartRequest;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Models\Part;
use App\Models\PartToTracking;
use App\Models\PartTracking;
use App\Models\PartTrackingType;
use App\Models\PartType;
use App\Models\PurchaseOrderItemType;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/part",
 *     tags={"Part"},
 *     summary="Create a new part",
 *     description="Store a new part in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"partNumber", "partDescription", "partDetails", "uom", "upc", "partType", "active"},
 *             @OA\Property(property="partNumber", type="string", example="10001"),
 *             @OA\Property(property="partDescription", type="string", example="High-quality widget"),
 *             @OA\Property(property="partDetails", type="string", example="Used in various applications, including XYZ"),
 *             @OA\Property(property="uom", type="string", example="Kilogram"),
 *             @OA\Property(property="upc", type="string", example="012345678912"),
 *             @OA\Property(property="partType", type="string", example="Overhead"),
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="abcCode", type="string", example="A"),
 *             @OA\Property(property="weight", type="number", format="float", example=1.5),
 *             @OA\Property(property="weightUom", type="integer", example=1),
 *             @OA\Property(property="width", type="number", format="float", example=10.0),
 *             @OA\Property(property="length", type="number", format="float", example=20.0),
 *             @OA\Property(property="sizeUom", type="integer", example=2),
 *             @OA\Property(property="consumptionRate", type="number", format="float", example=0.5),
 *             @OA\Property(property="alertNote", type="string", example="Handle with care; fragile"),
 *             @OA\Property(property="pictureUrl", type="string", example="https://www.example.com/images/part123456.jpg"),
 *             @OA\Property(property="revision", type="string", example="Rev1"),
 *             @OA\Property(property="poItemType", type="string", example="Purchase"),
 *             @OA\Property(property="defaultOutsourcedReturnItem", type="integer", example=123),
 *             @OA\Property(property="primaryTracking", type="string", example="Expiration Date"),
 *             @OA\Property(property="tracks", type="string", example="Expiration Date"),
 *             @OA\Property(property="nextValue", type="string", example="Next123"),
 *             @OA\Property(property="cf", type="string", example="CustomFieldValue")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Part created successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Part Created Successfully!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error message.")
 *         )
 *     )
 * )
 */
    public function store(StorePartRequest $storePartRequest): JsonResponse
        {
            $uom = UnitOfMeasure::where('name', $storePartRequest->uom)->firstOrFail();
            $partType = PartType::where('name', $storePartRequest->partType)->firstOrFail();
            $poItemType = PurchaseOrderItemType::where('name', $storePartRequest->poItemType)->firstOrFail();

            $part = Part::create(
                $storePartRequest->only([
                    'partDetails',
                    'upc',
                    'weight',
                    'width',
                    'consumptionRate',
                    'revision',
                    'length',
                ]) + [
                    'num' => $storePartRequest->partNumber,
                    'description' => $storePartRequest->partDescription,
                    'uomId' => $uom->id,
                    'typeId' => $partType->id,
                    'activeFlag' => $storePartRequest->active,
                    'weightUomId' => $storePartRequest->weightUom,
                    'sizeUomId' => $storePartRequest->sizeUom,
                    'url' => $storePartRequest->pictureUrl,
                    'defaultPoItemTypeId' => $poItemType->id,
                ]
            );

            $partTracking = PartTracking::where('name', $storePartRequest->primaryTracking)->firstOrFail();

            $partToTracking = PartToTracking::create(
                $storePartRequest->only('nextValue') + [
                    'partTrackingId' => $partTracking->id,
                    'partId' => $part->id,
                ]
            );

            $serialNumber = null;
            if ($storePartRequest->primaryTracking === 'Serial Number') {
                $serialNumber = SerialNumber::createUniqueSerialNumber($partToTracking->partTrackingId);
            }

            return response()->json(
                [
                    'message' => 'Part Created Successfully!',
                    'partData' => $part,
                    'partTrackingData' => $partTracking,
                    'partToTrackingData' => $partToTracking,
                    'serialNum' => $serialNumber,
                    'relatedData' => [
                        'uom' => $uom,
                        'partType' => $partType,
                        'poItemType' => $poItemType,
                        'partTracking' => $partTracking,
                    ],
                ],
                Response::HTTP_CREATED
            );
        }

    /**
     * @OA\Get(
     *     path="/api/part",
     *     tags={"Part"},
     *     summary="Display a specified part or all parts",
     *     description="Retrieve a part by its number or return all parts if no input is provided.",
     *     @OA\Parameter(
     *         name="num",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Part number to retrieve"
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="num", type="integer", description="Part number to retrieve")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Part(s) retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="num", type="integer", example=12345),
     *             @OA\Property(property="description", type="string", example="Description of the part."),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Part not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Part not found.")
     *         )
     *     )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $num = $request->query('num');

        if (!$num && $request->isJson()) {
            $num = $request->input('num');
        }

        if ($num) {
            $request->validate(['num' => 'integer|exists:part,num']);

            $part = Part::where('num', $num)->firstOrFail();
            return response()->json($part, Response::HTTP_OK);
        }

        $parts = Part::all();
        return response()->json($parts, Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/part",
     *     tags={"Part"},
     *     summary="Update a specific part",
     *     description="Updates the details of a specific part by `partId` from the JSON request.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"partId", "partNumber", "partDescription", "partDetails", "uom", "upc", "partType", "active"},
     *             @OA\Property(property="partId", type="integer", description="Part ID"),
     *             @OA\Property(property="partNumber", type="string", example="10001"),
     *             @OA\Property(property="partDescription", type="string", example="High-quality widget"),
     *             @OA\Property(property="partDetails", type="string", example="Used in various applications, including XYZ"),
     *             @OA\Property(property="uom", type="string", example="Kilogram"),
     *             @OA\Property(property="upc", type="string", example="012345678912"),
     *             @OA\Property(property="partType", type="string", example="Overhead"),
     *             @OA\Property(property="active", type="boolean", example=true),
     *             @OA\Property(property="abcCode", type="string", example="A"),
     *             @OA\Property(property="weight", type="number", format="float", example=1.5),
     *             @OA\Property(property="weightUom", type="integer", example=1),
     *             @OA\Property(property="width", type="number", format="float", example=10.0),
     *             @OA\Property(property="length", type="number", format="float", example=20.0),
     *             @OA\Property(property="sizeUom", type="integer", example=2),
     *             @OA\Property(property="consumptionRate", type="number", format="float", example=0.5),
     *             @OA\Property(property="alertNote", type="string", example="Handle with care; fragile"),
     *             @OA\Property(property="pictureUrl", type="string", example="https://www.example.com/images/part123456.jpg"),
     *             @OA\Property(property="revision", type="string", example="Rev1"),
     *             @OA\Property(property="poItemType", type="string", example="Purchase"),
     *             @OA\Property(property="primaryTracking", type="string", example="Expiration Date"),
     *             @OA\Property(property="nextValue", type="string", example="Next123"),
     *             @OA\Property(property="cf", type="string", example="CustomFieldValue")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Part updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product Updated Successfully!"),
     *             @OA\Property(property="partData", type="object", description="Updated part details"),
     *             @OA\Property(property="partTrackingData", type="object", description="Part tracking details"),
     *             @OA\Property(property="partToTrackingData", type="object", description="Part to tracking details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Part not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Part not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message.")
     *         )
     *     )
     * )
     */
    public function update($id, UpdatePartRequest $request): JsonResponse
    {
        $part = Part::findOrFail($id);

        $uom = UnitOfMeasure::where('name', $request->uom)->firstOrFail();
        $partType = PartType::where('name', $request->partType)->firstOrFail();
        $poItemType = PurchaseOrderItemType::where('name', $request->poItemType)->firstOrFail();
        $partTrackingType = PartTrackingType::where('name', $request->tracks)->firstOrFail();

        $part->update(
            $request->only([
                'partDetails',
                'upc',
                'weight',
                'width',
                'consumptionRate',
                'revision',
                'length',
            ]) + [
                'num' => $request->partNumber,
                'description' => $request->partDescription,
                'uomId' => $uom->id,
                'typeId' => $partType->id,
                'activeFlag' => $request->active,
                'weightUomId' => $request->weightUom,
                'sizeUomId' => $request->sizeUom,
                'url' => $request->pictureUrl,
                'defaultPoItemTypeId' => $poItemType->id,
            ]
        );

        return response()->json(
            [
                'message' => 'Product Updated Successfully!',
                'partData' => $part,
                'relatedData' => [
                    'uom' => $uom,
                    'partType' => $partType,
                    'poItemType' => $poItemType,
                    'partTrackingType' => $partTrackingType,
                ],
            ],
            Response::HTTP_OK
        );
    }

 
    public function destroy(Request $request): JsonResponse
    {
        $partId = $request->input('partId');

        $part = Part::find($partId);

        if (!$part) {
            return response()->json([
                'message' => 'Part not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $part->delete();

        return response()->json([
            'message' => 'Part deleted successfully!'
        ], Response::HTTP_OK);
    }

}
