<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryAndStateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/country-state/state",
     *     tags={"Country and State"},
     *     summary="Create a new state",
     *     description="Store a new state in the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stateName", "stateCode", "countryId"},
     *             @OA\Property(property="stateName", type="string", example="California"),
     *             @OA\Property(property="stateCode", type="string", example="CA"),
     *             @OA\Property(property="countryId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="State created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="State Created Successfully!"),
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
    public function storeState(Request $request): JsonResponse
    {
        if (!$request->isJson()) {
            return response()->json(
                ['message' => 'Invalid content type, expecting application/json.'],
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        $validated = $request->validate([
            'stateName' => 'required|string|max:255',
            'stateCode' => 'required|string|max:10',
            'countryId' => 'required|exists:countries,id',
        ]);

        $state = State::create([
            'name' => $validated['stateName'],
            'code' => $validated['stateCode'],
            'country_id' => $validated['countryId'],
        ]);

        return response()->json(
            [
                'message' => 'State Created Successfully!',
                'state' => $state,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     *     path="/api/country-state/countries",
     *     tags={"Country and State"},
     *     summary="Get all countries",
     *     description="Retrieve all countries from the database.",
     *     @OA\Response(
     *         response=200,
     *         description="Countries retrieved successfully.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="United States")
     *             )
     *         )
     *     )
     * )
     */
    public function getAllCountries(): JsonResponse
    {
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/country-state/states",
     *     tags={"Country and State"},
     *     summary="Get all states",
     *     description="Retrieve all states from the database.",
     *     @OA\Response(
     *         response=200,
     *         description="States retrieved successfully.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="California"),
     *                 @OA\Property(property="country_id", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function getAllStates(): JsonResponse
    {
        $states = State::all();

        return response()->json([
            'states' => $states,
        ], Response::HTTP_OK);
    }

     /**
     * @OA\Get(
     *     path="/api/country-state/country",
     *     tags={"Country and State"},
     *     summary="Get a country by ID",
     *     description="Retrieve a country by its ID.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"countryId"},
     *             @OA\Property(property="countryId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Country retrieved successfully.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Country not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Country not found.")
     *         )
     *     )
     * )
     */
    public function showCountryById(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'countryId' => 'required|integer|exists:country,id',
        ]);

        $country = Country::find($validated['countryId']);

        return response()->json([
            'country' => $country,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/country-state/state",
     *     tags={"Country and State"},
     *     summary="Get a state by ID",
     *     description="Retrieve a state by its ID.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stateId"},
     *             @OA\Property(property="stateId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="State retrieved successfully.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="State not found.")
     *         )
     *     )
     * )
     */
    public function showStateById(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stateId' => 'required|integer|exists:states,id',
        ]);

        $state = State::find($validated['stateId']);

        return response()->json([
            'state' => $state,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/country-state/state",
     *     tags={"Country and State"},
     *     summary="Update a state",
     *     description="Update an existing state in the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stateId", "stateName", "stateCode", "countryId"},
     *             @OA\Property(property="stateId", type="integer", example=1),
     *             @OA\Property(property="stateName", type="string", example="California"),
     *             @OA\Property(property="stateCode", type="string", example="CA"),
     *             @OA\Property(property="countryId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="State updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="State Updated Successfully!"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="State not found.")
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
    public function updateState(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stateId' => 'required|integer|exists:states,id',
            'stateName' => 'required|string|max:255',
            'stateCode' => 'required|string|max:10',
            'countryId' => 'required|exists:countries,id',
        ]);

        $state = State::find($validated['stateId']);
        $state->update([
            'name' => $validated['stateName'],
            'code' => $validated['stateCode'],
            'country_id' => $validated['countryId'],
        ]);

        return response()->json(['message' => 'State Updated Successfully!'], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/country-state/state",
     *     tags={"Country and State"},
     *     summary="Delete a state",
     *     description="Delete an existing state from the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stateId"},
     *             @OA\Property(property="stateId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="State deleted successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="State not found.")
     *         )
     *     )
     * )
     */
    public function deleteState(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stateId' => 'required|integer|exists:states,id',
        ]);

        $state = State::find($validated['stateId']);
        $state->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
