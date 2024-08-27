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

class PaymentTermsController extends Controller
{
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

        return response()->json(
            [
                'message' => 'Payment Terms Created Successfuly!',
                'data' => $paymentTerms
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentTerms $paymentTerms): JsonResponse
    {
        return response()->json($paymentTerms, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentTermsRequest $updatePaymentTermsRequest, PaymentTerms $paymentTerms): JsonResponse
    {
        $paymentTermsType = PaymentTermsType::where('name', $updatePaymentTermsRequest->termsType)->firstOrFail();


        $paymentTerms->update(
            $updatePaymentTermsRequest->only(
                [
                    'netDays',
                    'discount',
                    'discountDays',
                    'nextMonth'
                ]
            ) + [
                'name' => $updatePaymentTermsRequest->termsName,
                'typeId' => $paymentTermsType->id,
                'defaultTerms' => $updatePaymentTermsRequest->default,
                'activeFlag' => $updatePaymentTermsRequest->active,
            ]
        );

        return response()->json(
            [
                'message' => 'Payment Terms Updated Successfully!',
                'data' => $paymentTerms
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
