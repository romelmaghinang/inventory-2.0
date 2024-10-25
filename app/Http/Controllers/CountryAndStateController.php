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
        ]);

        $state = State::create([
            'name' => $validated['stateName'],
            'code' => $validated['stateCode'],
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
     *     path="/api/country-state/country",
     *     tags={"Country and State"},
     *     summary="Retrieve all countries or filter by name",
     *     description="Retrieve all countries if no query parameters or JSON body is provided. Optionally filter by country name using query parameters or JSON body.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="The name of the country to filter by",
     *         example="United States"
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="United States", description="Name of the country to filter by")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Countries retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="countries", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="United States")
     *                 )
     *             )
     *         )
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
    public function showCountry(Request $request): JsonResponse
    {
        $name = $request->query('name') ?? $request->input('name');

        if (empty($name)) {
            $countries = Country::all();
            return response()->json([
                'message' => 'All countries retrieved successfully!',
                'countries'  => $countries,
            ], Response::HTTP_OK);
        }

        $request->validate([
            'name' => 'string|exists:countries,name',
        ]);

        $country = Country::where('name', $name)->first();

        if (!$country) {
            return response()->json([
                'message' => 'Country not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Country retrieved successfully!',
            'country' => $country,
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Get(
     *     path="/api/country-state/state",
     *     tags={"Country and State"},
     *     summary="Retrieve all states or filter by name",
     *     description="Retrieve all states if no query parameters or JSON body is provided. Optionally filter by state name using query parameters or JSON body.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="The name of the state to filter by",
     *         example="California"
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="California", description="Name of the state to filter by")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="States retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="states", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="California"),
     *                     @OA\Property(property="country_id", type="integer", example=1)
     *                 )
     *             )
     *         )
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
    public function showState(Request $request): JsonResponse
    {
        $name = $request->query('name') ?? $request->input('name');

        if (empty($name)) {
            $states = State::all();
            return response()->json([
                'message' => 'All states retrieved successfully!',
                'states'  => $states,
            ], Response::HTTP_OK);
        }

        $request->validate([
            'name' => 'string|exists:state,name',
        ]);

        $state = State::where('name', $name)->first();

        if (!$state) {
            return response()->json([
                'message' => 'State not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Return the filtered state
        return response()->json([
            'message' => 'State retrieved successfully!',
            'state'   => $state,
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
            'stateId' => 'required|integer|exists:state,id',
            'stateName' => 'required|string|max:255',
            'stateCode' => 'required|string|max:10',
        ]);

        $state = State::find($validated['stateId']);
        $state->update([
            'name' => $validated['stateName'],
            'code' => $validated['stateCode'],
        ]);

        return response()->json(['message' => 'State Updated Successfully!'], Response::HTTP_OK);
    }


    public function deleteState(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stateId' => 'required|integer|exists:state,id',
        ]);

        $state = State::find($validated['stateId']);
        $state->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }


}
