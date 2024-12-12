<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuickBook\StoreQuickBookClassRequest;
use App\Http\Requests\QuickBook\UpdateQuickBookClassRequest;
use App\Models\qbClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuickBookClassController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/qbclass",
 *     summary="Create a new QuickBook class",
 *     tags={"QuickBookClass"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Sales"),
 *             @OA\Property(property="active", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Quick Book Class Created Successfully!",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Quick Book Class Created Successfully!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     )
 * )
 */
   public function store(StoreQuickBookClassRequest $storeQuickBookClassRequest): JsonResponse
    {
        $quickBook = qbClass::create($storeQuickBookClassRequest->only('name') + ['active' => $storeQuickBookClassRequest->active]);

        return response()->json(
            [
                'message' => 'Quick Book Class Created Successfully!',
                'data' => $quickBook
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(UpdateQuickBookClassRequest $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:qbclass,name,' . $id,
            'active' => 'required|boolean'
        ]);

        $quickBook = qbClass::find($id);

        if (!$quickBook) {
            return response()->json([
                'message' => 'Quick Book Class not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $quickBook->update(
            $request->only('name') + ['active' => $request->active]
        );

        return response()->json([
            'message' => 'Quick Book Class Updated Successfully!',
            'data' => $quickBook
        ], Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
   
    public function show(qbClass $qbClass): JsonResponse
    {
        return response()->json($qbClass, Response::HTTP_OK);
    }  */
/**
 * @OA\Get(
 *     path="/api/qbclass",
 *     summary="Retrieve QuickBook classes",
 *     tags={"QuickBookClass"},
 *     description="Fetches a specific QuickBook class by name or retrieves all classes if no name is provided.",
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="The name of the QuickBook class to retrieve",
 *         required=false,
 *         @OA\Schema(type="string", example="Class Name")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", description="The name of the QuickBook class to retrieve", example="Class Name")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="QuickBook Class retrieved successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="QuickBook Class retrieved successfully."),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="QuickBook Class not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="QuickBook Class not found.")
 *         )
 *     )
 * )
 */
    public function show(Request $request, $id = null): JsonResponse
    {
        if ($id) {
            $qbClass = qbClass::find($id);

            if (!$qbClass) {
                return response()->json(['message' => 'QuickBook Class not found.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'QuickBook Class retrieved successfully!',
                'data' => $qbClass,
            ], Response::HTTP_OK);
        }

        $nameFromQuery = $request->input('name');
        $nameFromBody = $request->json('name');

        if ($nameFromQuery || $nameFromBody) {
            $name = $nameFromQuery ?? $nameFromBody;

            $request->validate([
                'name' => 'required|string|exists:qbclass,name',
            ]);

            $qbClass = qbClass::where('name', $name)->first();

            if (!$qbClass) {
                return response()->json(['message' => 'QuickBook Class not found.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'QuickBook Class retrieved successfully!',
                'data' => $qbClass,
            ], Response::HTTP_OK);
        }

        $perPage = $request->input('per_page', 100);

        $qbClasses = qbClass::paginate($perPage);

        return response()->json([
            'message' => 'All QuickBook Classes retrieved successfully!',
            'data' => $qbClasses->items(),
            'pagination' => [
                'total' => $qbClasses->total(),
                'per_page' => $qbClasses->perPage(),
                'current_page' => $qbClasses->currentPage(),
                'last_page' => $qbClasses->lastPage(),
                'next_page_url' => $qbClasses->nextPageUrl(),
                'prev_page_url' => $qbClasses->previousPageUrl(),
            ],
        ], Response::HTTP_OK);
    }

   
    
     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'qbclassId' => 'required|integer|exists:qbclass,id'
        ]);
    
        $qbClass = qbClass::find($request->qbclassId);
    
        if (!$qbClass) {
            return response()->json([
                'message' => 'Quick Book Class not found.'
            ], Response::HTTP_NOT_FOUND);
        }
    
        $qbClass->delete();
    
        return response()->json([
            'message' => 'Quick Book Class Deleted Successfully!'
        ], Response::HTTP_OK);
    }
}
