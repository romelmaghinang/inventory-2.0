<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/currency",
 *     tags={"Currency"},
 *     summary="Create a new currency",
 *     description="Creates a new currency with details such as name, code, active flag, and currency rates.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="US Dollar", description="Name of the currency"),
 *             @OA\Property(property="code", type="string", example="USD", description="Currency code"),
 *             @OA\Property(property="active", type="boolean", example=true, description="Active status of the currency"),
 *             @OA\Property(property="quickBookCurrencyRate", type="number", format="float", example=1.0, description="QuickBooks currency rate"),
 *             @OA\Property(property="globalCurrencyRate", type="number", format="float", example=1.0, description="Global currency rate")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Currency Created Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Currency Created Successfully!"),
 *             @OA\Property(property="data", type="object", description="Created currency object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(
 *             @OA\Property(property="errors", type="object", description="Validation errors")
 *         )
 *     )
 * )
 */
   public function store(StoreCurrencyRequest $storeCurrencyRequest): JsonResponse
    {
        $currency = Currency::create($storeCurrencyRequest->only(
            [
                'name',
                'code',
            ]
        ) +
            [
                'activeFlag' => $storeCurrencyRequest->active,
                'rate' => $storeCurrencyRequest->globalCurrencyRate,
            ]);

        return response()->json(
            [
                'message' => 'Currency Created Successfully!',
                'data' => $currency,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency): JsonResponse
    {
        return response()->json($currency, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $updateCurrencyRequest, Currency $currency): JsonResponse
    {
        $currency->update(
            $updateCurrencyRequest->only(
                [
                    'name',
                    'code'
                ]
            ) + [
                'activeFlag' => $updateCurrencyRequest->active,
                'rate' => $updateCurrencyRequest->globalCurrencyRate,
            ]
        );

        return response()->json(
            [
                'message' => 'Currency Update Successfully!',
                'data' => $currency,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency): JsonResponse
    {
        $currency->delete();

        return response()->json(
            [
                'message' => 'Currency Deleted Succesfully!'
            ],
            Response::HTTP_OK
        );
    }
}
