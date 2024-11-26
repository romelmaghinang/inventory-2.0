<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class CountryAndStateController extends Controller
{
    protected function failedValidation(Validator $validator)
    {
        $categorizedErrors = [];

        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                if (str_contains($message, 'required')) {
                    $categorizedErrors['missingRequiredFields'][] = $field;
                } elseif (str_contains($message, 'must be') || str_contains($message, 'Invalid')) {
                    $categorizedErrors['invalidFormat'][] = $field;
                } elseif (str_contains($message, 'has already been taken')) {
                    $categorizedErrors['duplicateFields'][] = $field;
                } elseif (str_contains($message, 'exists')) {
                    $categorizedErrors['relatedFieldErrors'][] = $field;
                }
            }
        }

        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }

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
            'abbreviation' => 'required|string|max:10',
        ]);

        $state = State::create([
            'name' => $validated['stateName'],
            'code' => $validated['abbreviation'],
        ]);

        return response()->json(
            [
                'message' => 'State Created Successfully!',
                'state' => $state,
            ],
            Response::HTTP_CREATED
        );
    }

    public function showCountry(Request $request): JsonResponse
    {
        $name = $request->query('name') ?? $request->input('name');

        if (empty($name)) {
            $countries = Country::all();
            return response()->json([
                'message' => 'All countries retrieved successfully!',
                'countries' => $countries,
            ], Response::HTTP_OK);
        }

        $request->validate([
            'name' => 'string|exists:country,name',
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

    public function showState(Request $request): JsonResponse
    {
        $name = $request->query('name') ?? $request->input('name');

        if (empty($name)) {
            $states = State::all();
            return response()->json([
                'message' => 'All states retrieved successfully!',
                'states' => $states,
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

        return response()->json([
            'message' => 'State retrieved successfully!',
            'state' => $state,
        ], Response::HTTP_OK);
    }

    public function updateState(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stateId' => 'required|integer|exists:state,id',
            'stateName' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10',
        ]);

        $state = State::find($validated['stateId']);
        $state->update([
            'name' => $validated['stateName'],
            'code' => $validated['abbreviation'],
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
