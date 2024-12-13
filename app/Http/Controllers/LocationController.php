<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Customer;
use App\Models\qbClass;
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
        $locationGroup = LocationGroup::firstOrCreate(
            ['name' => $storeLocationRequest->locationGroup],
            ['qbClassId' => 1] 
        );
        
        $qbClass = qbClass::find(1);


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
                'relatedData' => [
                    'locationType' => $locationType,
                    'locationGroup' => $locationGroup,
                    'customer' => $customer,
                    'qbClass' => $qbClass, 
                ],
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
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'location' => 'nullable|string',
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
    
        $location = Location::findOrFail($id);
    
        $locationType = null;
        $locationGroup = null;
        $customer = null;
    
        if ($request->has('type')) {
            $locationType = LocationType::where('name', $request->type)->firstOrFail();
        }
    
        if ($request->has('locationGroup')) {
            $locationGroup = LocationGroup::firstOrCreate(['name' => $request->locationGroup]);
        }
    
        if ($request->has('customerName')) {
            $customer = Customer::where('name', $request->customerName)->firstOrFail();
        }
    
        $updateData = [];
    
        if ($request->has('location')) {
            $updateData['name'] = $request->location;
        }
    
        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }
    
        if ($request->has('active')) {
            $updateData['active'] = $request->active;
        }
    
        if ($request->has('available')) {
            $updateData['available'] = $request->available;
        }
    
        if ($request->has('pickable')) {
            $updateData['pickable'] = $request->pickable;
        }
    
        if ($request->has('receivable')) {
            $updateData['receivable'] = $request->receivable;
        }
    
        if ($request->has('sortOrder')) {
            $updateData['sortOrder'] = $request->sortOrder;
        }
    
        if ($locationType) {
            $updateData['typeId'] = $locationType->id;
        }
    
        if ($locationGroup) {
            $updateData['locationGroupId'] = $locationGroup->id;
        }
    
        if ($customer) {
            $updateData['defaultCustomerId'] = $customer->id;
        }
    
        $location->update($updateData);
    
        return response()->json(
            [
                'message' => 'Location updated successfully!',
                'location' => $location,
                'relatedData' => [
                    'locationType' => $locationType,
                    'locationGroup' => $locationGroup,
                    'customer' => $customer,
                ],
            ],
            Response::HTTP_OK
        );
    }
    
    
/**
 * @OA\Get(
 *     path="/api/location",
 *     tags={"Location"},
 *     summary="Get location details",
 *     description="Retrieve a specific location by name or all locations if no name is provided.",
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", example="Main"),
 *         description="Name of the location to retrieve"
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Main", description="Name of the location to retrieve")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Location details retrieved successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Main"),
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
public function show(Request $request, $id = null): JsonResponse  
{
    $type = $request->query('type', $request->input('type'));  
    $name = $request->query('name', $request->input('name'));  
    $pickable = $request->query('pickable', $request->input('pickable'));  
    $receivable = $request->query('receivable', $request->input('receivable')); 
    $activeFlag = $request->query('activeFlag', $request->input('activeFlag'));  
    $locationGroup = $request->query('locationGroup', $request->input('locationGroup')); 
    

    if ($id) {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'message' => 'Location not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $locationType = LocationType::find($location->typeId);
        $locationGroup = LocationGroup::find($location->locationGroupId);

        $locationData = $location->toArray();
        $locationData['type'] = $locationType ? [
            'id' => $locationType->id,
            'name' => $locationType->name,
        ] : null;
        $locationData['locationGroup'] = $locationGroup ? $locationGroup->toArray() : null;

        return response()->json([
            'message' => 'Location retrieved successfully!',
            'data' => $locationData,
        ], Response::HTTP_OK);
    }

    $query = Location::query();

    if ($type) {
        $request->validate(['type' => 'string|exists:locationtype,name']);
        
        $locationType = LocationType::where('name', $type)->first();
        
        if ($locationType) {
            $query->where('typeId', $locationType->id);
        } else {
            return response()->json([
                'message' => 'LocationType with the provided name not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    if ($name) {
        $query->where('name', 'like', '%' . $name . '%');
    }

    if ($pickable !== null) {
        $query->where('pickable', filter_var($pickable, FILTER_VALIDATE_BOOLEAN) ? 1 : 0);
    }

    if ($receivable !== null) {
        $query->where('receivable', filter_var($receivable, FILTER_VALIDATE_BOOLEAN) ? 1 : 0);
    }

    if ($activeFlag !== null) {
        $query->where('activeFlag', filter_var($activeFlag, FILTER_VALIDATE_BOOLEAN) ? 1 : 0);
    }

    if ($locationGroup) {
        $group = LocationGroup::where('name', $locationGroup)->first();

        if ($group) {
            $query->where('locationGroupId', $group->id);
        } else {
            return response()->json([
                'message' => 'LocationGroup with the provided name not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    $perPage = $request->input('per_page', 100);
    $locations = $query->paginate($perPage);

    $locationsData = $locations->items();
    foreach ($locationsData as &$location) {
        $locationType = LocationType::find($location['typeId']);
        $location['type'] = $locationType ? [
            'id' => $locationType->id,
            'name' => $locationType->name,
        ] : null;

        $locationGroup = LocationGroup::find($location['locationGroupId']);
        $location['locationGroup'] = $locationGroup ? $locationGroup->toArray() : null;
    }

    return response()->json([
        'message' => 'All locations retrieved successfully!',
        'data' => $locationsData,
        'pagination' => [
            'total' => $locations->total(),
            'per_page' => $locations->perPage(),
            'current_page' => $locations->currentPage(),
            'last_page' => $locations->lastPage(),
            'next_page_url' => $locations->nextPageUrl(),
            'prev_page_url' => $locations->previousPageUrl(),
        ],
    ], Response::HTTP_OK);
}
    public function showLocationType(Request $request): JsonResponse
    {
        $locationTypes = LocationType::paginate(100); 

        return response()->json([
            'message' => 'Location Types retrieved successfully!',
            'data' => $locationTypes,
        ], Response::HTTP_OK);
    }
    public function showLocationGroup(Request $request): JsonResponse
    {
        $locationGroups = LocationGroup::paginate(100); 

        foreach ($locationGroups as $locationGroup) {
            $qbClass = QBClass::find($locationGroup->qbClassId);
            
            $locationGroup->qbClassName = $qbClass ? $qbClass->name : null;
        }

        return response()->json([
            'message' => 'Location Groups retrieved successfully!',
            'data' => $locationGroups,
        ], Response::HTTP_OK);
    }






    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['locationId' => 'required|integer|exists:location,id']);

        $location = Location::findOrFail($request->locationId);

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully!'], Response::HTTP_OK);
    }

}
