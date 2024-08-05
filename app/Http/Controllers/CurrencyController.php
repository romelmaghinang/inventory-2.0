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
