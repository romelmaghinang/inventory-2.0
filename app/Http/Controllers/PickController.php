<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Http\Requests\Pick\UpdatePickRequest;
use App\Models\Inventory;
use App\Models\Tag;
use App\Models\Location;
use App\Models\Part;
use App\Models\Pick;
use App\Models\PartTracking;
use App\Models\PickItem;
use App\Models\Product;
use App\Models\SerialNumber;
use App\Models\TrackingInfo;
use App\Models\TableReference;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\TrackingInfoSn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class PickController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/pick",
 *     tags={"Pick"},
 *     summary="Store a new pick",
 *     description="This endpoint allows the user to create a new pick entry based on the sales order and tracking information.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="pickNum", type="integer", example=10001, description="The Sales Order number for the pick."),
 *             @OA\Property(property="locationName", type="string", example="Main", description="The name of the location from which the pick is made."),
 *             @OA\Property(property="partNum", type="string", example="10002", description="The part number being picked."),
 *             @OA\Property(property="partTrackingType", type="string", example="Lot Number", description="The type of tracking for the part."),
 *             @OA\Property(property="trackingInfo", type="array", @OA\Items(type="string", example="LT0001"), description="An array of tracking information related to the pick.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Picked Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Picked Successfully!", description="Success message indicating the pick was created."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Sales Order with pickNum 10001 not found.", description="Error message indicating the requested resource was not found.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Insufficient quantity.", description="Error message indicating a validation issue.")
 *         )
 *     )
 * )
 */
    public function store(StorePickRequest $storePickRequest): JsonResponse
    {
        $part = Part::where('num', $storePickRequest->partNum)->firstOrFail();
        $partTracking = PartTracking::where('name', $storePickRequest->partTrackingType)->firstOrFail();
    
        $tableReference = TableReference::where('tableRefName', 'PickItem')->first();
        if (!$tableReference) {
            return response()->json(
                [
                    'message' => 'TableReference with PickItem not found.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        $tableId = $tableReference->tableId;
    
        $so = SalesOrder::where('num', $storePickRequest->pickNum)->first();
        if (!$so) {
            return response()->json(
                [
                    'message' => "Sales Order with pickNum {$storePickRequest->pickNum} not found.",
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    
        $soItem = SalesOrderItems::where('soid', $so->id)->first();
        if (!$soItem) {
            return response()->json(
                [
                    'message' => "Sales Order Items for Sales Order ID {$so->id} not found.",
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    
        $qty = $soItem->qtyOrdered;
        $trackingInfos = [];
    
        if ($storePickRequest->partTrackingType === 'Serial Number') {
            foreach ($storePickRequest->validated()['trackingInfo'] as $serialNumber) {
                try {
                    SerialNumber::where('serialNum', $serialNumber)
                        ->where('partTrackingId', $partTracking->id)
                        ->firstOrFail();
    
                    $locationGroupId = $so->locationGroupId;
    
                    $tag = Tag::where('locationId', $locationGroupId)->first();
                    if ($tag) {
                        if ($tag->qty < $qty) {
                            return response()->json(
                                [
                                    'message' => 'Insufficient quantity.',
                                ],
                                Response::HTTP_BAD_REQUEST
                            );
                        }
    
                        $tag->qty -= $qty;
                        $tag->save();
                    } else {
                        return response()->json(
                            [
                                'message' => "Tag with locationGroupId {$locationGroupId} not found.",
                            ],
                            Response::HTTP_NOT_FOUND
                        );
                    }
    
                    $inventory = Inventory::where('locationGroupId', $locationGroupId)->first();
                    if ($inventory) {
                        if ($inventory->qtyOnHand < $qty) {
                            return response()->json(
                                [
                                    'message' => 'Insufficient quantity.',
                                ],
                                Response::HTTP_BAD_REQUEST
                            );
                        }
    
                        $inventory->qtyOnHand -= $qty;
                        $inventory->save();
                    } else {
                        return response()->json(
                            [
                                'message' => "Inventory with locationGroupId {$locationGroupId} not found.",
                            ],
                            Response::HTTP_NOT_FOUND
                        );
                    }
    
                    $trackingInfo = TrackingInfo::create([
                        'partTrackingId' => $partTracking->id,
                        'qty' => $qty,
                        'tableId' => $tableId,
                    ]);
    
                    TrackingInfoSn::create([
                        'partTrackingId' => $partTracking->id,
                        'serialNum' => $serialNumber,
                        'trackingInfoId' => $trackingInfo->id,
                    ]);
    
                    $trackingInfos[] = $trackingInfo;
                } catch (ModelNotFoundException $e) {
                    return response()->json(
                        [
                            'message' => "Serial Number $serialNumber does not exist.",
                        ],
                        Response::HTTP_NOT_FOUND
                    );
                }
            }
        } else {
            foreach ($storePickRequest->trackingInfo as $info) {
                $trackingInfoData = [
                    'partTrackingId' => $partTracking->id,
                    'qty' => $qty,
                    'tableId' => $tableId,
                ];
    
                switch ($storePickRequest->partTrackingType) {
                    case 'Expiration Date':
                        $trackingInfoData['infoDate'] = $info;
                        break;
                    case 'Revision Level':
                    case 'Lot Number':
                        $trackingInfoData['info'] = $info;
                        break;
                    default:
                        return response()->json(
                            [
                                'message' => 'Invalid part tracking type.',
                            ],
                            Response::HTTP_BAD_REQUEST
                        );
                }
    
                $trackingInfo = TrackingInfo::create($trackingInfoData);
                $trackingInfos[] = $trackingInfo;
            }
        }
    
        $location = Location::where('name', $storePickRequest->locationName)->firstOrFail();
    
        $so->update(['statusId' => 25]);
    
        $soItem->update(['statusId' => 40]);
    
        return response()->json(
            [
                'message' => 'Picked Successfully!',
                'trackingInfos' => $trackingInfos,
            ],
            Response::HTTP_CREATED
        );
    }
     /**
     * @OA\Put(
     *     path="/api/pick",
     *     tags={"Pick"},
     *     summary="Update a specific pick",
     *     description="Update an existing pick by ID using a JSON request body.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pickId"},
     *             @OA\Property(property="pickId", type="integer", example=1, description="The ID of the pick to update."),
     *             @OA\Property(property="locationName", type="string", example="Main Updated", description="Updated location name."),
     *             @OA\Property(property="partNum", type="string", example="10002", description="Updated part number."),
     *             @OA\Property(property="trackingInfo", type="array", @OA\Items(type="string", example="LT0001"), description="Updated tracking information.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pick updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pick updated successfully!", description="Success message indicating the pick was updated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pick not found.",
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
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'locationName' => 'required|string',
            'partNum' => 'required|string',
            'trackingInfo' => 'nullable|array',
        ]);
    
        $pick = Pick::findOrFail($id);
    
        $pick->update($request->only(['locationName', 'partNum', 'trackingInfo']));
    
        return response()->json(['message' => 'Pick updated successfully!', 'pick' => $pick], Response::HTTP_OK);
    }
    
    /**
     * @OA\Get(
     *     path="/api/pick",
     *     tags={"Pick"},
     *     summary="Show all picks or a specific pick",
     *     description="Retrieve all picks or a specific pick by number using a JSON request body or query parameters. Additionally, picks can be filtered by creation date using `createdBefore` and `createdAfter`.",
     *     @OA\Parameter(
     *         name="num",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="The number of the pick to retrieve."
     *     ),
     *     @OA\Parameter(
     *         name="createdBefore",
     *         in="query",
     *         description="Retrieve picks created before this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="createdAfter",
     *         in="query",
     *         description="Retrieve picks created after this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="num", type="string", example="PN-123", description="The number of the pick to retrieve.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Picks retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="picks", type="array", @OA\Items(type="object"), description="Array of all pick objects or a single pick object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pick not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found.")
     *         )
     *     )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $numFromQuery = $request->query('num');
        $numFromBody = $request->input('num');
        
        $createdBefore = $request->input('createdBefore');
        $createdAfter = $request->input('createdAfter');
        
        $num = $numFromQuery ?? $numFromBody;

        if ($num) {
            $request->validate([
                'num' => 'required|string|exists:pick,num',
            ]);

            $pick = Pick::where('num', $num)->firstOrFail();
            return response()->json(
                [
                    'picks' => [$pick] 
                ],
                Response::HTTP_OK
            );
        }

        $query = Pick::query();

        if ($createdBefore) {
            $request->validate([
                'createdBefore' => 'date|before_or_equal:today',
            ]);
            $query->whereDate('dateCreated', '<=', $createdBefore);
        }

        if ($createdAfter) {
            $request->validate([
                'createdAfter' => 'date|before_or_equal:today',
            ]);
            $query->whereDate('dateCreated', '>=', $createdAfter);
        }

        $picks = $query->get();

        return response()->json(
            [
                'picks' => $picks,
            ],
            Response::HTTP_OK
        );
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['pickId' => 'required|integer|exists:pick,id']);

        $pick = Pick::findOrFail($request->pickId);
        $pick->delete();

        return response()->json(['message' => 'Pick deleted successfully!'], Response::HTTP_OK);
    }

}