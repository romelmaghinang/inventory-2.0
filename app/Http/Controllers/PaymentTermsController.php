<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTerms\StorePaymentTermsRequest;
use App\Http\Requests\PaymentTerms\UpdatePaymentTermsRequest;
use App\Models\PaymentTerms;
use App\Models\PaymentTermsType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class PaymentTermsController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/payment-terms",
 *     tags={"Payment Terms"},
 *     summary="Create new payment terms",
 *     description="Creates new payment terms with provided details such as name, type, net days, discount, etc.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="termsName", type="string", example="Net 30", description="Name of the payment terms"),
 *             @OA\Property(property="termsType", type="string", example="CCD", description="Type of payment terms"),
 *             @OA\Property(property="netDays", type="integer", example=30, description="Net days for the payment"),
 *             @OA\Property(property="discount", type="number", format="float", example=5.0, description="Discount percentage"),
 *             @OA\Property(property="discountDays", type="integer", example=10, description="Days to apply the discount"),
 *             @OA\Property(property="dueDate", type="string", format="date", example="2024-09-01", description="Due date for the payment"),
 *             @OA\Property(property="nextMonth", type="string", format="date", example="2024-09-01", description="Next month's date"),
 *             @OA\Property(property="discountDate", type="string", format="date", example="2024-08-15", description="Discount date"),
 *             @OA\Property(property="default", type="boolean", example=true, description="Flag for default terms"),
 *             @OA\Property(property="active", type="boolean", example=true, description="Flag for active status"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Payment Terms Created Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Payment Terms Created Successfully!"),
 *             @OA\Property(property="data", type="object", description="Created payment terms object")
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
    public function store(StorePaymentTermsRequest $storePaymentTermsRequest): JsonResponse
    {
        $paymentTermsType = PaymentTermsType::where('name', $storePaymentTermsRequest->termsType)->firstOrFail();

        $paymentTerms = PaymentTerms::create(
            $storePaymentTermsRequest->only(
                [
                    'netDays',
                    'discount',
                    'discountDays',
                    'nextMonth'
                ]
            ) + [
                'name' => $storePaymentTermsRequest->termsName,
                'typeId' => $paymentTermsType->id,
                'defaultTerm' => $storePaymentTermsRequest->default,
                'activeFlag' => $storePaymentTermsRequest->active,
            ]
        );

        $relatedPaymentTermsType = $paymentTermsType;

        return response()->json(
            [
                'message' => 'Payment Terms Created Successfully!',
                'data' => $paymentTerms,
                'relatedData' => [
                    'paymentTermsType' => $relatedPaymentTermsType
                ]
            ],
            Response::HTTP_CREATED
        );
    }

    /**
    public function show(PaymentTerms $paymentTerms): JsonResponse
    {
        return response()->json($paymentTerms, Response::HTTP_OK);
    }*/

/**
 * @OA\Get(
 *     path="/api/payment-terms",
 *     summary="Get payment terms by name or all payment terms",
 *     tags={"Payment Terms"},
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string"),
 *         description="Name of the payment terms to retrieve"
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", description="Name of the payment terms to retrieve")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment Terms retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Payment Terms retrieved successfully!"),
 *             @OA\Property(property="data", type="object", description="Payment Terms object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Payment Terms not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Payment Terms not found.")
 *         )
 *     )
 * )
 */
public function show(Request $request, $id = null): JsonResponse
{
    if ($id) {
        $paymentTerm = PaymentTerms::find($id);

        if (!$paymentTerm) {
            return response()->json(['message' => 'Payment Term not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Payment Term retrieved successfully!',
            'data' => $paymentTerm,
        ], Response::HTTP_OK);
    }

    $name = $request->query('name');

    if (!$name && $request->isJson()) {
        $name = $request->input('name');
    }

    if ($name) {
        $request->validate(['name' => 'string|exists:paymentterms,name']);

        $paymentTerm = PaymentTerms::where('name', $name)->firstOrFail();

        return response()->json(
            [
                'message' => 'Payment Term retrieved successfully!',
                'data' => $paymentTerm,
            ],
            Response::HTTP_OK
        );
    }

    $paymentTerms = PaymentTerms::all();
    return response()->json(
        [
            'message' => 'All Payment Terms retrieved successfully!',
            'data' => $paymentTerms,
        ],
        Response::HTTP_OK
    );
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentTermsRequest $updatePaymentTermsRequest, PaymentTerms $paymentTerms, $id): JsonResponse
    {
        $paymentTerms = PaymentTerms::findOrFail($id);

        $paymentTermsType = PaymentTermsType::where('name', $updatePaymentTermsRequest->termsType)->firstOrFail();

        $updateData = $updatePaymentTermsRequest->only([
            'netDays', 
            'discount', 
            'discountDays', 
            'nextMonth', 
            'termsName', 
            'default', 
            'active'
        ]);

        $updateData = array_filter($updateData, function($value) {
            return !is_null($value);
        });

        $updateData = array_merge($updateData, [
            'name' => $updatePaymentTermsRequest->termsName,
            'typeId' => $paymentTermsType->id,
            'defaultTerms' => $updatePaymentTermsRequest->default,
            'activeFlag' => $updatePaymentTermsRequest->active,
        ]);

        $paymentTerms->update($updateData);

        $relatedPaymentTermsType = $paymentTermsType;

        return response()->json(
            [
                'message' => 'Payment Terms Updated Successfully!',
                'data' => $paymentTerms,
                'relatedData' => [
                    'paymentTermsType' => $relatedPaymentTermsType
                ]
            ],
            Response::HTTP_OK
        );
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTerms $paymentTerms): JsonResponse
    {
        $paymentTerms->delete();

        return response()->json(
            [
                'message' => 'Payment Terms Deleted Successfuly!',
            ],
            Response::HTTP_OK
        );
    }
}
