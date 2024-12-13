<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

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
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'JPY' => '¥',
            'GBP' => '£',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'INR' => '₹',
            'BRL' => 'R$',
            'RUB' => '₽',
            'ZAR' => 'R',
            'MXN' => '$',
            'KRW' => '₩',
            'SAR' => 'ر.س',
            'TRY' => '₺',
            'SGD' => 'S$',
            'MYR' => 'RM',
            'NZD' => 'NZ$',
            'THB' => '฿',
            'IDR' => 'Rp',
            'ILS' => '₪',
            'NOK' => 'kr',
            'SEK' => 'kr',
            'DKK' => 'kr',
            'PLN' => 'zł',
            'HUF' => 'Ft',
            'CZK' => 'Kč',
            'HKD' => 'HK$',
            'PHP' => '₱',
            'VND' => '₫',
            'ARS' => '$',
            'CLP' => '$',
            'COP' => '$',
            'EGP' => '£',
            'AED' => 'د.إ',
            'QAR' => 'ر.ق',
            'BDT' => '৳',
            'PKR' => '₨',     
            'LKR' => 'Rs'
        ];
        

        $symbol = $currencySymbols[$storeCurrencyRequest->code] ?? null;

        if (!$symbol) {
            return response()->json(
                [
                    'message' => 'Invalid currency code. No symbol found for the provided code.',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $currency = Currency::create($storeCurrencyRequest->only(
            [
                'name',
                'code',
            ]
        ) +
            [
                'symbol' => $symbol,
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
 
    public function show(Currency $currency): JsonResponse
    {
        return response()->json($currency, Response::HTTP_OK);
    }    */
        /**
         * @OA\Get(
         *     path="/api/currency",
         *     tags={"Currency"},
         *     summary="Get currencies or filter by name",
         *     description="Retrieves all currencies if no query parameters or JSON body is provided. Filters by name using either query parameters or JSON body.",
         *     @OA\Parameter(
         *         name="name",
         *         in="query",
         *         required=false,
         *         @OA\Schema(type="string"),
         *         description="Name of the currency to filter by",
         *         example="USD"
         *     ),
         *     @OA\RequestBody(
         *         required=false,
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="name", type="string", example="USD", description="Name of the currency to filter by")
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Currency retrieved successfully",
         *         @OA\JsonContent(
         *             @OA\Property(property="data", type="object", description="Currency object or list of currencies"),
         *             @OA\Property(property="message", type="string", example="Currency retrieved successfully!")
         *         )
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Currency not found",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Currency not found.")
         *         )
         *     )
         * )
         */
        public function showCurrencies(Request $request, $id = null): JsonResponse
        {
            if ($id) {
                $currency = Currency::find($id);
        
                if (!$currency) {
                    return response()->json([
                        'message' => 'Currency not found.'
                    ], Response::HTTP_NOT_FOUND);
                }
        
                return response()->json(
                    [
                        'message' => 'Currency retrieved successfully!',
                        'data' => $currency,
                    ],
                    Response::HTTP_OK
                );
            }
        
            $name = $request->query('name') ?? $request->input('name');
            
            $query = Currency::query();
        
            if (!empty($name)) {
                $request->validate([
                    'name' => 'string|exists:currency,name',
                ]);
                
                $query->where('name', 'like', '%' . $name . '%');
            }
        
            $perPage = $request->query('per_page', $request->input('per_page', 100));
                    
            $currencies = $query->paginate($perPage);
        
            return response()->json(
                [
                    'message' => 'Currencies retrieved successfully!',
                    'data' => $currencies->items(),
                    'pagination' => [
                        'total' => $currencies->total(),
                        'per_page' => $currencies->perPage(),
                        'current_page' => $currencies->currentPage(),
                        'last_page' => $currencies->lastPage(),
                        'next_page_url' => $currencies->nextPageUrl(),
                        'prev_page_url' => $currencies->previousPageUrl(),
                    ],
                ],
                Response::HTTP_OK
            );
        }
        

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $updateCurrencyRequest, Currency $currency): JsonResponse
    {
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'JPY' => '¥',
            'GBP' => '£',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'INR' => '₹',
            'BRL' => 'R$',
            'RUB' => '₽',
            'ZAR' => 'R',
            'MXN' => '$',
            'KRW' => '₩',
            'SAR' => 'ر.س',
            'TRY' => '₺',
            'SGD' => 'S$',
            'MYR' => 'RM',
            'NZD' => 'NZ$',
            'THB' => '฿',
            'IDR' => 'Rp',
            'ILS' => '₪',
            'NOK' => 'kr',
            'SEK' => 'kr',
            'DKK' => 'kr',
            'PLN' => 'zł',
            'HUF' => 'Ft',
            'CZK' => 'Kč',
            'HKD' => 'HK$',
            'PHP' => '₱',
            'VND' => '₫',
            'ARS' => '$',
            'CLP' => '$',
            'COP' => '$',
            'EGP' => '£',
            'AED' => 'د.إ',
            'QAR' => 'ر.ق',
            'BDT' => '৳',
            'PKR' => '₨',     
            'LKR' => 'Rs'
        ];
    
        if ($updateCurrencyRequest->has('code')) {
            $symbol = $currencySymbols[$updateCurrencyRequest->code] ?? null;
    
            if (!$symbol) {
                return response()->json(
                    [
                        'message' => 'Invalid currency code. No symbol found for the provided code.',
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
    
            $updateCurrencyRequest->merge(['symbol' => $symbol]);
        }
    
        $currency->update(
            $updateCurrencyRequest->only(['name', 'code', 'symbol']) + 
            ($updateCurrencyRequest->has('active') ? ['activeFlag' => $updateCurrencyRequest->active] : []) + 
            ($updateCurrencyRequest->has('globalCurrencyRate') ? ['rate' => $updateCurrencyRequest->globalCurrencyRate] : [])
        );
    
        return response()->json(
            [
                'message' => 'Currency Updated Successfully!',
                'data' => $currency,
            ],
            Response::HTTP_OK
        );
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);
    
        $code = $request->input('code') ?? $request->code;
    
        $currency = Currency::where('code', $code)->first();
    
        if (!$currency) {
            return response()->json(
                [
                    'message' => 'Currency not found.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    
        $currency->delete();
    
        return response()->json(
            [
                'message' => 'Currency Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
    
}
