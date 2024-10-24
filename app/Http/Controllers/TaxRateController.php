<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRate\StoreTaxRateRequest;
use App\Http\Requests\TaxRate\UpdateTaxRateRequest;
use App\Models\TaxRate;
use App\Models\TaxRateType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxRateController extends Controller
{
    
    /**
 * @OA\Post(
 *     path="/api/taxrate",
 *     summary="Create a new tax rate",
 *     tags={"TaxRate"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="taxName", type="string", example="Standard Tax"),
 *             @OA\Property(property="taxCode", type="string", example="STX"),
 *             @OA\Property(property="taxType", type="string", example="Percentage"),
 *             @OA\Property(property="description", type="string", example="Standard sales tax rate"),
 *             @OA\Property(property="rate", type="number", format="float", example=0.07),
 *             @OA\Property(property="amount", type="number", format="float", example=100.0),
 *             @OA\Property(property="taxAgencyName", type="string", example="IRS"),
 *             @OA\Property(property="defaultFlag", type="boolean", example=true),
 *             @OA\Property(property="activeFlag", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tax Rate Created Successfully!",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Tax Rate Created Successfully!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     )
 * )
 */
    public function store(StoreTaxRateRequest $storeTaxRateRequest): JsonResponse
    {
        $taxRateType = TaxRateType::where(['name' => $storeTaxRateRequest->taxType])->firstOrFail();


        $taxRate = TaxRate::create($storeTaxRateRequest->only(
            [
                'rate',
                'description',
                'defaultFlag',
                'activeFlag',
            ]
        ) +
            [
                'name' => $storeTaxRateRequest->taxName,
                'code' => $storeTaxRateRequest->taxCode,
                'typeId' => $taxRateType->id,
            ]);

        return response()->json(
            [
                'message' => 'Tax Rate Created Successfully!',
                'data' => $taxRate
            ],
            Response::HTTP_CREATED
        );
    }

  /**
 * @OA\Get(
 *     path="/api/taxrate",
 *     summary="Retrieve tax rates",
 *     tags={"TaxRate"},
 *     description="Fetches a specific tax rate by name or retrieves all tax rates if no name is provided.",
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="The name of the tax rate to retrieve",
 *         required=false,
 *         @OA\Schema(type="string", example="Standard Rate")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", description="The name of the tax rate to retrieve", example="Standard Rate")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tax Rate retrieved successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Tax Rate retrieved successfully."),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tax Rate not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Tax Rate not found.")
 *         )
 *     )
 * )
 */

public function show(Request $request): JsonResponse
{
    $nameFromQuery = $request->input('name');
    $nameFromBody = $request->json('name');

    if ($nameFromQuery || $nameFromBody) {
        $name = $nameFromQuery ?? $nameFromBody;

        $request->validate([
            'name' => 'required|string|exists:taxrate,name',
        ]);

        $taxRate = TaxRate::where('name', $name)->first();

        if (!$taxRate) {
            return response()->json(['message' => 'Tax Rate not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Tax Rate retrieved successfully!',
            'data' => $taxRate,
        ], Response::HTTP_OK);
    }

    $taxRates = TaxRate::all();

    return response()->json([
        'message' => 'All Tax Rates retrieved successfully!',
        'data' => $taxRates,
    ], Response::HTTP_OK);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxRateRequest $updateTaxRateRequest, TaxRate $taxRate): JsonResponse
    {

        $taxRateType = TaxRateType::where(['name' => $updateTaxRateRequest->taxType])->firstOrFail();


        try {
            // Update the tax rate fields
            $taxRate->update(
                $updateTaxRateRequest->only(
                    'rate',
                    'description',
                    'defaultFlag',
                    'activeFlag'
                ) + [
                    'name' => $updateTaxRateRequest->taxName,
                    'code' => $updateTaxRateRequest->taxCode,
                    'typeId' => $taxRateType->id,
                ]
            );

            return response()->json(
                [
                    'message' => 'Tax Rate Updated Successfully!',
                    'data' => $taxRate
                ],
                Response::HTTP_OK
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tax Rate not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the tax rate'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxRate $taxRate): JsonResponse
    {
        $taxRate->delete();

        return response()->json(
            [
                'message' => 'Tax Rate Deleted Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
