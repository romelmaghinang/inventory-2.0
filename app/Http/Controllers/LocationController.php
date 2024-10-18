<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Customer;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/location",
 *     tags={"Location"},
 *     summary="Create a new location",
 *     description="Store a new location in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"location", "description", "type", "locationGroup", "customerName"},
 *             @OA\Property(property="location", type="string", example="Main"),
 *             @OA\Property(property="description", type="string", example="Primary storage location for all parts and materials."),
 *             @OA\Property(property="type", type="string", example="Stock"),
 *             @OA\Property(property="locationGroup", type="string", example="Main"),
 *             @OA\Property(property="locationNum", type="integer", example=101),
 *             @OA\Property(property="customerName", type="string", example="Acme Corp"),
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="available", type="boolean", example=true),
 *             @OA\Property(property="pickable", type="boolean", example=false),
 *             @OA\Property(property="receivable", type="boolean", example=true),
 *             @OA\Property(property="sortOrder", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Location created successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Location Created Successfully!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Location type or customer not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not Found.")
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
    public function store(StoreLocationRequest $storeLocationRequest): JsonResponse
    {
        $locationType = LocationType::where('name', $storeLocationRequest->type)->firstOrFail();
        $locationGroup = LocationGroup::firstOrCreate(['name' => $storeLocationRequest->locationGroup]);
        $customer = Customer::where('name', $storeLocationRequest->customerName)->firstOrFail();

        $location = Location::create(
            $storeLocationRequest->only(
                [
                    'description',
                    'pickable',
                    'receivable',
                    'sortOrder'
                ]
            ) +
                [
                    'name' => $storeLocationRequest->location,
                    'typeId' => $locationType->id,
                    'locationGroupId' => $locationGroup->id,
                    'defaultCustomerId' => $customer->id,
                    'activeFlag' => $storeLocationRequest->active,
                    'countedAsAvailable' => $storeLocationRequest->available,
                ]
        );

        return response()->json(
            [
                'message' => 'Location Created Successfully!',
                'location' => $location,
            ],
            Response::HTTP_CREATED
        );
    }
    /**
     * @OA\Put(
     *     path="/api/location",
     *     tags={"Location"},
     *     summary="Update a specific location",
     *     description="Update an existing location by ID using a JSON request body.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locationId"},
     *             @OA\Property(property="locationId", type="integer", example=1),
     *             @OA\Property(property="location", type="string", example="Main Updated"),
     *             @OA\Property(property="description", type="string", example="Updated description."),
     *             @OA\Property(property="type", type="string", example="Stock"),
     *             @OA\Property(property="locationGroup", type="string", example="Main"),
     *             @OA\Property(property="customerName", type="string", example="Acme Corp"),
     *             @OA\Property(property="active", type="boolean", example=true),
     *             @OA\Property(property="available", type="boolean", example=true),
     *             @OA\Property(property="pickable", type="boolean", example=false),
     *             @OA\Property(property="receivable", type="boolean", example=true),
     *             @OA\Property(property="sortOrder", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Location updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location updated successfully!"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found.")
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
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'locationId' => 'required|integer|exists:locations,id',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'locationGroup' => 'nullable|string',
            'customerName' => 'nullable|string',
            'active' => 'nullable|boolean',
            'available' => 'nullable|boolean',
            'pickable' => 'nullable|boolean',
            'receivable' => 'nullable|boolean',
            'sortOrder' => 'nullable|integer',
        ]);

        $location = Location::findOrFail($request->locationId);

        $location->update($request->only([
            'location',
            'description',
            'type',
            'locationGroup',
            'customerName',
            'active',
            'available',
            'pickable',
            'receivable',
            'sortOrder',
        ]));

        return response()->json(['message' => 'Location updated successfully!', 'location' => $location], Response::HTTP_OK);
    }
/**
 * @OA\Get(
 *     path="/api/location",
 *     tags={"Location"},
 *     summary="Get location details",
 *     description="Retrieve a specific location by ID or all locations.",
 *     @OA\Parameter(
 *         name="locationId",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="ID of the location to retrieve"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Location details retrieved successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="location", type="string", example="Main"),
 *             @OA\Property(property="description", type="string", example="Primary storage location for all parts and materials."),
 *             @OA\Property(property="typeId", type="integer", example=1),
 *             @OA\Property(property="locationGroupId", type="integer", example=1),
 *             @OA\Property(property="defaultCustomerId", type="integer", example=1),
 *             @OA\Property(property="activeFlag", type="boolean", example=true),
 *             @OA\Property(property="countedAsAvailable", type="boolean", example=true),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Location not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not Found.")
 *         )
 *     )
 * )
 */
    public function show(Request $request): JsonResponse
    {
        if ($request->has('locationId')) {
            $request->validate(['locationId' => 'required|integer|exists:locations,id']);
            
            $location = Location::findOrFail($request->locationId);
            return response()->json($location, Response::HTTP_OK);
        }

        $locations = Location::all();
        return response()->json($locations, Response::HTTP_OK);
    }


   /**
     * @OA\Delete(
     *     path="/api/location",
     *     tags={"Location"},
     *     summary="Delete a specific location",
     *     description="Delete a specific location by ID using a JSON request body.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locationId"},
     *             @OA\Property(property="locationId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Location deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location deleted successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Location not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found.")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['locationId' => 'required|integer|exists:location,id']);

        $location = Location::findOrFail($request->locationId);

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully!'], Response::HTTP_OK);
    }

}
