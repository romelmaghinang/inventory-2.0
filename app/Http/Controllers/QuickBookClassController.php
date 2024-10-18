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

    /**
     * Display the specified resource.
     */
    public function show(qbClass $qbClass): JsonResponse
    {
        return response()->json($qbClass, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuickBookClassRequest $updateQuickBookClassRequest, qbClass $qbClass): JsonResponse
    {
        $qbClass->update($updateQuickBookClassRequest->only('name') + ['active' => $updateQuickBookClassRequest->active]);

        return response()->json(
            [
                'message' => 'Quick Book Class Updated Successfully!',
                'data' => $qbClass,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(qbClass $qbClass): JsonResponse
    {
        $qbClass->delete();

        return response()->json(
            [
                'message' => 'Quick Book Class Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
