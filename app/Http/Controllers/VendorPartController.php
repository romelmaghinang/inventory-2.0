<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\VendorPart;
use App\Models\Vendor;
use App\Models\UnitOfMeasure;
use App\Models\Part;
use Illuminate\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;

class VendorPartController extends Controller
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
    
        return response()->json(
            [
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => array_filter($categorizedErrors),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
    
    /**
     * Store a new vendor part.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->isJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request must be JSON.',
            ], 400);
        }
    
        $data = $request->json()->all();
    
        try {
            $validated = validator($data, [
                'Vendor' => 'required|string|max:41',
                'FishbowlPartNumber' => 'nullable|string|max:90',
                'VendorPartNumber' => 'required|string|max:90',
                'Cost' => 'required|numeric',
                'UOM' => 'nullable|string|max:30',
                'LeadTime' => 'nullable|numeric|min:0',
                'DefaultVendor' => 'nullable|boolean',
                'MinQty' => 'nullable|numeric|min:0',
            ])->validate();
        } catch (ValidationException $e) {
            return $this->failedValidation($e->validator);
        }
    
        $vendor = Vendor::where('name', $validated['Vendor'])->first();
        if (!$vendor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vendor not found.',
            ], 422);
        }
    
        $uom = UnitOfMeasure::where('name', $validated['UOM'] ?? 'ea')->first();
        if (!$uom) {
            return response()->json([
                'status' => 'error',
                'message' => 'UOM not found.',
            ], 422);
        }
    
        $part = Part::where('num', $validated['VendorPartNumber'])->first();
        if (!$part) {
            return response()->json([
                'status' => 'error',
                'message' => 'Part not found.',
            ], 422);
        }
    
        $existingVendorPart = VendorPart::where('vendorId', $vendor->id)
            ->where('vendorPartNumber', $validated['VendorPartNumber'])
            ->first();
    
        if ($existingVendorPart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Duplicate vendor part number for this vendor.',
            ], 422);
        }
    
        $vendorPart = VendorPart::create([
            'vendorId' => $vendor->id,
            'fishbowl_part_number' => $validated['FishbowlPartNumber'],
            'vendorPartNumber' => $validated['VendorPartNumber'],
            'cost' => $validated['Cost'],
            'uomId' => $uom->id,
            'lead_time' => $validated['LeadTime'] ?? 0,
            'default_flag' => $validated['DefaultVendor'] ?? false,
            'qty_min' => $validated['MinQty'] ?? 0,
            'partId' => $part->id,
        ]);
    
        $vendorPart->load(['vendor', 'uom', 'part']);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Vendor part created successfully.',
            'data' => [
                'vendorPart' => $vendorPart,
                'relatedData' => [
                    'Vendor' => $vendorPart->vendor,
                    'UOM' => $vendorPart->uom,
                    'Part' => $vendorPart->part,
                ],
            ],
        ], 201);
    }
    

    /**
     * Update an existing vendor part.
     */
    public function update(Request $request, $id): JsonResponse
    {
        if (!$request->isJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request must be JSON.',
            ], 400);
        }
    
        $data = $request->json()->all();
    
        try {
            $validated = validator($data, [
                'Vendor' => 'nullable|string|max:41',
                'FishbowlPartNumber' => 'nullable|string|max:90',
                'VendorPartNumber' => 'nullable|string|max:90',
                'Cost' => 'nullable|numeric',
                'UOM' => 'nullable|string|max:30',
                'LeadTime' => 'nullable|numeric|min:0',
                'DefaultVendor' => 'nullable|boolean',
                'MinQty' => 'nullable|numeric|min:0',
            ])->validate();
        } catch (ValidationException $e) {
            return $this->failedValidation($e->validator);
        }
    
        $vendorPart = VendorPart::findOrFail($id);
    
        if (isset($validated['Vendor'])) {
            $vendor = Vendor::where('name', $validated['Vendor'])->first();
            if (!$vendor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vendor not found.',
                ], 422);
            }
            $vendorPart->vendorId = $vendor->id;
        }
    
        if (isset($validated['UOM'])) {
            $uom = UnitOfMeasure::where('name', $validated['UOM'])->first();
            if (!$uom) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'UOM not found.',
                ], 422);
            }
            $vendorPart->uomId = $uom->id;
        }
    
        if (isset($validated['VendorPartNumber'])) {
            $part = Part::where('num', $validated['VendorPartNumber'])->first();
            if (!$part) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Part not found.',
                ], 422);
            }
    
            $duplicateVendorPart = VendorPart::where('vendorId', $vendorPart->vendorId)
                ->where('vendorPartNumber', $validated['VendorPartNumber'])
                ->where('id', '!=', $vendorPart->id) 
                ->first();
    
            if ($duplicateVendorPart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate vendor part number for this vendor.',
                ], 422);
            }
    
            $vendorPart->partId = $part->id;
        }
    
        $vendorPart->update(
            array_filter([
                'fishbowl_part_number' => $validated['FishbowlPartNumber'] ?? $vendorPart->fishbowl_part_number,
                'cost' => $validated['Cost'] ?? $vendorPart->cost,
                'lead_time' => $validated['LeadTime'] ?? $vendorPart->lead_time,
                'default_flag' => $validated['DefaultVendor'] ?? $vendorPart->default_flag,
                'qty_min' => $validated['MinQty'] ?? $vendorPart->qty_min,
            ])
        );
    
        $vendorPart->load(['vendor', 'uom', 'part']);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Vendor part updated successfully.',
            'data' => [
                'vendorPart' => $vendorPart,
                'relatedData' => [
                    'Vendor' => $vendorPart->vendor,
                    'UOM' => $vendorPart->uom,
                    'Part' => $vendorPart->part,
                ],
            ],
        ]);
    }
    

    

    /**
     * Show a single vendor part.
     */
    public function show(Request $request, $id = null)
    {
        $page = $request->query('page', 1);
        $perPage = 10;
        
        $vendorPartNumberFilter = $request->query('vendorPartNumber', null);
    
        if (is_null($id)) {
            $query = VendorPart::with(['vendor', 'uom', 'part']);
            
            if ($vendorPartNumberFilter) {
                $query->where('vendorPartNumber', 'like', '%' . $vendorPartNumberFilter . '%');
            }
    
            $allVendorParts = $query->paginate($perPage, ['*'], 'page', $page);
    
            return response()->json([
                'vendorParts' => $allVendorParts->items(),
                'pagination' => [
                    'total' => $allVendorParts->total(),
                    'current_page' => $allVendorParts->currentPage(),
                    'last_page' => $allVendorParts->lastPage(),
                    'per_page' => $allVendorParts->perPage(),
                ],
            ]);
        }
    
        $vendorPart = VendorPart::with(['vendor', 'uom', 'part'])
            ->where('id', $id)
            ->first();
    
        if (!$vendorPart) {
            return response()->json(['error' => 'Vendor part not found.'], 404);
        }
    
        $query = VendorPart::where('vendorId', $vendorPart->vendorId);
    
        if ($vendorPartNumberFilter) {
            $query->where('vendorPartNumber', 'like', '%' . $vendorPartNumberFilter . '%');
        }
    
        $relatedParts = $query->paginate($perPage, ['*'], 'page', $page);
    
        $relatedPartsTransformed = $relatedParts->map(function ($relatedPart) {
            return [
                'vendor' => Vendor::find($relatedPart->vendorId),
                'uom' => UnitOfMeasure::find($relatedPart->uomId),
                'part' => Part::find($relatedPart->partId)
            ];
        });
    
        return response()->json([
            'vendorPart' => [
                'id' => $vendorPart->id,
                'vendor' => Vendor::find($vendorPart->vendorId),
                'uom' => UnitOfMeasure::find($vendorPart->uomId),
                'part' => Part::find($vendorPart->partId),
                'vendorPartNumber' => $vendorPart->vendor_part_number,
                'cost' => $vendorPart->cost,
                'defaultFlag' => $vendorPart->default_flag,
                'qtyMin' => $vendorPart->qty_min,
            ],
            'relatedParts' => $relatedPartsTransformed,
            'pagination' => [
                'total' => $relatedParts->total(),
                'current_page' => $relatedParts->currentPage(),
                'last_page' => $relatedParts->lastPage(),
                'per_page' => $relatedParts->perPage(),
            ],
        ]);
    }
    
    /**
     * Delete a vendor part.
     */
    public function destroy($id)
    {
        $vendorPart = VendorPart::find($id);

        if (!$vendorPart) {
            return response()->json(['error' => 'Vendor part not found.'], 404);
        }

        $vendorPart->delete();

        return response()->json(['message' => 'Vendor part deleted successfully.']);
    }
}
