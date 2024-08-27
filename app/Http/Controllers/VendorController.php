<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Currency;
use App\Models\ShipTerms;
use App\Models\State;
use App\Models\Vendor;
use App\Models\VendorStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorController extends Controller
{
    public function store(StoreVendorRequest $storeVendorRequest): JsonResponse
    {
        $state = State::where('name', $storeVendorRequest->state)->firstOrFail();
        $country = Country::where('name', $storeVendorRequest->country)->firstOrFail();
        $currency = Currency::where('name', $storeVendorRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $storeVendorRequest->defaultCarrier)->firstOrFail();
        $shipterms = ShipTerms::where('name', $storeVendorRequest->defaultShippingTerms)->firstOrFail();
        $vendorStatus = VendorStatus::where('name', $storeVendorRequest->status)->firstOrFail();

        $address = Address::create(
            $storeVendorRequest->only(
                [
                    'address',
                    'city',
                    'zip',
                ]
            )
                +
                [
                    'typeId' => $storeVendorRequest->addressType,
                    'defaultFlag' => $storeVendorRequest->isDefault,
                    'stateId' => $state->id,
                    'countryId' => $country->id,
                    'name' => $storeVendorRequest->addressName,
                ]
        );

        $vendor = Vendor::create(
            $storeVendorRequest->only(
                [
                    'name',
                    'currencyRate',
                    'minOrderAmount',
                    'url',
                ]
            ) +
                [
                    'currencyId' => $currency->id,
                    'defaultCarrierId' => $carrier->id,
                    'defaultShipTermsId' => $shipterms->id,
                    'statusId' => $vendorStatus->id,
                    'accountNum' => $storeVendorRequest->accountNumber,
                    'activeFlag' => $storeVendorRequest->active,
                    'note' => $storeVendorRequest->alertNotes,
                ]
        );

        return response()->json(
            [
                'message' => 'Vendor Created Successfully!',
                'address' => $address,
                'vendor' => $vendor,
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor): JsonResponse
    {
        return response()->json($vendor, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendorRequest $updateVendorRequest, Vendor $vendor): JsonResponse
    {
        $state = State::where('name', $updateVendorRequest->state)->firstOrFail();
        $country = Country::where('name', $updateVendorRequest->country)->firstOrFail();
        $currency = Currency::where('name', $updateVendorRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $updateVendorRequest->defaultCarrier)->firstOrFail();
        $shipterms = ShipTerms::where('name', $updateVendorRequest->defaultShippingTerms)->firstOrFail();
        $vendorStatus = VendorStatus::where('name', $updateVendorRequest->status)->firstOrFail();

        $address = Address::firstOrCreate(
            ['addressName' => $updateVendorRequest->addressName],
            [
                'address' => $updateVendorRequest->address,
                'city' => $updateVendorRequest->city,
                'zip' => $updateVendorRequest->zip,
                'pipelineContactNum' => $updateVendorRequest->addressContact,
                'typeId' => $updateVendorRequest->addressType,
                'defaultFlag' => $updateVendorRequest->isDefault,
                'stateId' => $state->id,
                'countryId' => $country->id,
            ]
        );

        $vendor->update(
            $updateVendorRequest->only(
                [
                    'name',
                    'currencyRate',
                    'minOrderAmount',
                    'url',
                ]
            )
                +
                [
                    'currencyId' => $currency->id,
                    'defaultCarrierId' => $carrier->id,
                    'defaultShipTermsId' => $shipterms->id,
                    'statusId' => $vendorStatus->id,
                    'accountNum' => $updateVendorRequest->accountNumber,
                    'activeFlag' => $updateVendorRequest->active,
                    'note' => $updateVendorRequest->alertNotes,
                ]
        );

        return response()->json(
            [
                'message' => 'Vendor Updated Successfully!',
                'address' => $address,
                'vendor' => $vendor,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor): JsonResponse
    {
        $vendor->delete();

        return response()->json(
            [
                'message' => 'Vendor Deleted Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
