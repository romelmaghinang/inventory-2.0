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

/**
 * @OA\Post(
 *     path="api/vendor",
 *     summary="Create a new vendor",
 *     tags={"Vendor"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="addressName", type="string", example="Headquarter"),
 *             @OA\Property(property="addressContact", type="string", example="Jane Smith"),
 *             @OA\Property(property="addressType", type="integer", example=10),
 *             @OA\Property(property="isDefault", type="boolean", example=true),
 *             @OA\Property(property="address", type="string", example="123 Main St"),
 *             @OA\Property(property="city", type="string", example="Springfield"),
 *             @OA\Property(property="state", type="string", example="California"),
 *             @OA\Property(property="zip", type="string", example="12345"),
 *             @OA\Property(property="country", type="string", example="United States"),
 *             @OA\Property(property="main", type="string", example="555-1234"),
 *             @OA\Property(property="home", type="string", example="555-5678"),
 *             @OA\Property(property="work", type="string", example="555-8765"),
 *             @OA\Property(property="mobile", type="string", example="555-4321"),
 *             @OA\Property(property="fax", type="string", example="555-9876"),
 *             @OA\Property(property="email", type="string", example="example@example.com"),
 *             @OA\Property(property="pager", type="string", example="555-6789"),
 *             @OA\Property(property="web", type="string", example="https://www.example.com"),
 *             @OA\Property(property="other", type="string", example="N/A"),
 *             @OA\Property(property="currencyName", type="string", example="US Dollar"),
 *             @OA\Property(property="currencyRate", type="number", format="float", example=1.0),
 *             @OA\Property(property="defaultTerms", type="string", example="Net 30"),
 *             @OA\Property(property="defaultCarrier", type="string", example="FedEx"),
 *             @OA\Property(property="defaultShippingTerms", type="string", example="Prepaid"),
 *             @OA\Property(property="status", type="string", example="Normal"),
 *             @OA\Property(property="accountNumber", type="string", example="A123456789"),
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="minOrderAmount", type="number", format="float", example=100.0),
 *             @OA\Property(property="alertNotes", type="string", example="Handle with care"),
 *             @OA\Property(property="url", type="string", example="https://www.examplevendor.com"),
 *             @OA\Property(property="cf", type="string", example="CF123456789")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Vendor Created Successfully!",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Vendor Created Successfully!"),
 *   
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     )
 * )
 */

    
    public function store(StoreVendorRequest $storeVendorRequest): JsonResponse
    {
        $state = State::where('name', $storeVendorRequest->state)->firstOrFail();
        $country = Country::where('name', $storeVendorRequest->country)->firstOrFail();
        $currency = Currency::where('name', $storeVendorRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $storeVendorRequest->defaultCarrier)->firstOrFail();
        $shipterms = ShipTerms::where('name', $storeVendorRequest->defaultShippingTerms)->firstOrFail();
        $vendorStatus = VendorStatus::where('name', $storeVendorRequest->status)->firstOrFail();
    
        $address = Address::create(
            $storeVendorRequest->only(['address', 'city', 'zip']) +
            [
                'typeId' => $storeVendorRequest->addressType,
                'defaultFlag' => $storeVendorRequest->isDefault,
                'stateId' => $state->id,
                'countryId' => $country->id,
                'name' => $storeVendorRequest->addressName,
            ]
        );
    
        $vendor = Vendor::create(
            $storeVendorRequest->only(['name', 'currencyRate', 'minOrderAmount', 'url']) +
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
                'relatedData' => [
                    'state' => $state,
                    'country' => $country,
                    'currency' => $currency,
                    'carrier' => $carrier,
                    'shipterms' => $shipterms,
                    'vendorStatus' => $vendorStatus,
                ],
            ],
            Response::HTTP_CREATED
        );
    }
 
 /**
 * @OA\Get(
 *     path="/api/vendor",
 *     summary="Get vendor(s) details",
 *     description="Retrieve a specific vendor's information by their name from either the JSON request body or query parameters. If no vendorName is provided, return all vendors.",
 *     operationId="getVendor",
 *     tags={"Vendor"},
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Name of the vendor to retrieve",
 *         required=false,
 *         @OA\Schema(type="string", example="Vendor Name")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="vendorName", type="string", example="Vendor Name", description="Name of the vendor to retrieve")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Vendor(s) details retrieved successfully",
 *         @OA\JsonContent(
 *             oneOf={
 *                 @OA\Schema(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Vendor Name"),
 *                     @OA\Property(property="email", type="string", example="vendor@example.com"),
 *                     @OA\Property(property="address", type="string", example="123 Vendor Street"),
 *                     @OA\Property(property="phone", type="string", example="123-456-7890")
 *                 ),
 *                 @OA\Schema(
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Vendor Name"),
 *                         @OA\Property(property="email", type="string", example="vendor@example.com"),
 *                         @OA\Property(property="address", type="string", example="123 Vendor Street"),
 *                         @OA\Property(property="phone", type="string", example="123-456-7890")
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Vendor not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Vendor not found")
 *         )
 *     )
 * )
 */

    public function show(Request $request, $id = null): JsonResponse
    {
        if ($id) {
            $vendor = Vendor::find($id);

            if (!$vendor) {
                return response()->json(['message' => 'Vendor not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($vendor, Response::HTTP_OK);
        }

        $vendorNameFromQuery = $request->input('name');
        $vendorNameFromBody = $request->json('name');

        if ($vendorNameFromQuery || $vendorNameFromBody) {
            $vendorName = $vendorNameFromQuery ?? $vendorNameFromBody;

            $vendor = Vendor::where('name', $vendorName)->first();

            if (!$vendor) {
                return response()->json(['message' => 'Vendor not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($vendor, Response::HTTP_OK);
        }

        $vendors = Vendor::all();
        return response()->json($vendors, Response::HTTP_OK);
    }

 
    /**
     * @OA\Put(
     *      path="/api/vendor",
     *      operationId="updateVendor",
     *      tags={"Vendor"},
     *      summary="Update an existing vendor",
     *      description="Updates a vendor based on the JSON request data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="vendorId", type="integer", description="Vendor ID"),
     *              @OA\Property(property="name", type="string", description="Vendor name"),
     *              @OA\Property(property="addressName", type="string", description="Address name"),
     *              @OA\Property(property="state", type="string", description="State"),
     *              @OA\Property(property="country", type="string", description="Country"),
     *              @OA\Property(property="currencyName", type="string", description="Currency name"),
     *              @OA\Property(property="defaultCarrier", type="string", description="Default carrier"),
     *              @OA\Property(property="defaultShippingTerms", type="string", description="Default shipping terms"),
     *              @OA\Property(property="status", type="string", description="Vendor status"),
     *              @OA\Property(property="address", type="string", description="Address"),
     *              @OA\Property(property="city", type="string", description="City"),
     *              @OA\Property(property="zip", type="string", description="ZIP code"),
     *              @OA\Property(property="active", type="boolean", description="Is vendor active"),
     *              @OA\Property(property="alertNotes", type="string", description="Alert notes"),
     *              example={
     *                  "vendorId": 1,
     *                  "name": "Updated Vendor",
     *                  "addressName": "Vendor HQ",
     *                  "state": "California",
     *                  "country": "United States",
     *                  "currencyName": "USD",
     *                  "defaultCarrier": "FedEx",
     *                  "defaultShippingTerms": "FOB",
     *                  "status": "Active",
     *                  "address": "123 Main St",
     *                  "city": "Los Angeles",
     *                  "zip": "90001",
     *                  "active": true,
     *                  "alertNotes": "Handle with care"
     *              }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Vendor updated successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Vendor Updated Successfully!"),
     *              @OA\Property(property="vendor", type="object"),
     *              @OA\Property(property="address", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Vendor not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Vendor not found")
     *          )
     *      )
     * )
     */
        public function update(UpdateVendorRequest $updateVendorRequest, $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        $state = State::where('name', $updateVendorRequest->state)->firstOrFail();
        $country = Country::where('name', $updateVendorRequest->country)->firstOrFail();
        $currency = Currency::where('name', $updateVendorRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $updateVendorRequest->defaultCarrier)->firstOrFail();
        $shipterms = ShipTerms::where('name', $updateVendorRequest->defaultShippingTerms)->firstOrFail();
        $vendorStatus = VendorStatus::where('name', $updateVendorRequest->status)->firstOrFail();

        $address = Address::updateOrCreate(
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
            $updateVendorRequest->only(['name', 'currencyRate', 'minOrderAmount', 'url']) + [
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
                'vendor' => $vendor,
                'address' => $address,
                'relatedData' => [
                    'state' => $state,
                    'country' => $country,
                    'currency' => $currency,
                    'carrier' => $carrier,
                    'shipterms' => $shipterms,
                    'vendorStatus' => $vendorStatus,
                ],
            ],
            Response::HTTP_OK
        );
    }



    /**
     * @OA\Delete(
     *     path="/api/vendor",
     *     tags={"Vendor"},
     *     summary="Delete a specific vendor",
     *     description="Deletes a specific vendor by vendor ID provided in the JSON request body.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="vendorId", type="integer", description="ID of the vendor to delete")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vendor Deleted Successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendor not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vendor not found.")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $vendorId = $request->input('vendorId');

        $vendor = Vendor::find($vendorId);

        if (!$vendor) {
            return response()->json([
                'message' => 'Missing vendor ID.'
            ], Response::HTTP_NOT_FOUND);
        }

        $vendor->delete();

        return response()->json([
            'message' => 'Vendor Deleted Successfully!'
        ], Response::HTTP_OK);
    }

}
