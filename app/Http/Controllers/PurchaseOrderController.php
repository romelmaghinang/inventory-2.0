<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Currency;
use App\Models\LocationGroup;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\qbClass;
use App\Models\ShipTerms;
use App\Models\State;
use App\Models\TaxRate;
use App\Models\UnitOfMeasure;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseOrderController extends Controller
{
    private function findModel($modelClass, $field, $value)
    {
        try {
            return $modelClass::where($field, $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "{$modelClass} not found."], 404);
        }
    }

    private function handleFindModels($request)
    {
        $models = [];
    
        try {
            $models['remitCountry'] = Country::where('name', $request->RemitToCountry)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Remit country not found.'], 404);
        }
    
        try {
            $models['remitState'] = State::where('name', $request->RemitToState)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Remit state not found.'], 404);
        }
    
        try {
            $models['shipToCountry'] = Country::where('name', $request->ShipToCountry)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Ship to country not found.'], 404);
        }
    
        try {
            $models['shipToState'] = State::where('name', $request->ShipToState)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Ship to state not found.'], 404);
        }
    
        try {
            $models['qbClass'] = qbClass::where('name', $request->QuickBooksClassName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'QB class not found.'], 404);
        }
    
        try {
            $models['currency'] = Currency::where('name', $request->CurrencyName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Currency not found.'], 404);
        }
    
        try {
            $models['carrier'] = Carrier::where('name', $request->CarrierName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carrier not found.'], 404);
        }
    
        try {
            $models['shipTerms'] = ShipTerms::where('name', $request->ShippingTerms)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shipping terms not found.'], 404);
        }
    
        try {
            $models['locationGroup'] = LocationGroup::where('name', $request->LocationGroupName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Location group not found.'], 404);
        }
        
    
        return $models; 
    }
    

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $models = $this->handleFindModels($request);
        if ($models instanceof JsonResponse) return $models; 

        $taxRateId = optional(TaxRate::where('name', $request->taxRateName)->first())->id;

        $newNum = (string)((optional(PurchaseOrder::latest('id')->first())->num ?? 1000) + 1);

        $purchaseOrder = PurchaseOrder::create(array_merge(
            $request->only([
                'buyer', 'dateIssued', 'dateConfirmed', 'dateCompleted', 'dateFirstShip',
                'dateRevision', 'vendorSO', 'customerSO', 'phone', 'email', 'url', 'note',
                'deliverTo', 'paymentTermsId', 'fobPointId', 'remitToName', 'remitAddress',
                'remitCity', 'remitZip', 'shipToName', 'shipToAddress', 'shipToCity', 'shipToZip',
                'username',
            ]),
            [
                'buyerId' => $request->buyerId ?? 0,
                'num' => $request->poNum ?? $newNum,
                'locationGroupId' => $models['locationGroup']->id,
                'carrierId' => $models['carrier']->id,
                'currencyId' => $models['currency']->id,
                'currencyRate' => $request->currencyRate,
                'shipTermsId' => $models['shipTerms']->id,
                'remitCountryId' => $models['remitCountry']->id,
                'remitStateId' => $models['remitState']->id,
                'shipToCountryId' => $models['shipToCountry']->id,
                'shipToStateId' => $models['shipToState']->id,
                'qbClassId' => $models['qbClass']->id,
                'statusId' => $request->statusId ?? 20,
                'taxRateId' => $taxRateId,
                'totalIncludesTax' => $request->totalIncludesTax,
                'totalTax' => $request->totalTax,
                'typeId' => $request->typeId,
            ]
        ));

        $purchaseOrderItems = collect($request->validated()['items'])->map(function ($item) use ($purchaseOrder) {
            if (!isset($item['UOM'])) {
                return response()->json(['error' => 'UOM is required for each item.'], 422);
            }
            try {
                $uom = UnitOfMeasure::where('name', $item['UOM'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                
                return response()->json(['error' => 'Unit of Measure not found: ' . $item['UOM']], 404);
            }
            
            try {
                $qbClass = qbClass::where('name', $item['QuickBooksClassName'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'QuickBooks Class not found: ' . $item['QuickBooksClassName']], 404);
            }
            

            return PurchaseOrderItem::create([
                'description' => '',
                'note' => $item['Note'],
                'partNum' => $item['PartNumber'],
                'unitCost' => '',
                'totalCost' => '',
                'qtyToFulfill' => '',
                'dateScheduledFulfillment' => $item['FulfillmentDate'],
                'revLevel' => $item['RevisionLevel'],
                'vendorPartNum' => $item['VendorPartNumber'],
                'uomId' => $uom->id,
                'poId' => $purchaseOrder->id,
                'qbClassId' => $qbClass->id,
                'taxId' => '',
                'taxRate' => '',
                'statusId' => '',
                'repairFlag' => '',
                'tbdCostFlag' => '',
            ]);
        });

        return response()->json([
            'message' => 'Purchase Order created successfully',
            'purchaseOrderData' => $purchaseOrder,
            'purchaseOrderItemData' => $purchaseOrderItems,
        ], Response::HTTP_CREATED);
    }

    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return response()->json($purchaseOrder, Response::HTTP_OK);
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $models = $this->handleFindModels($request);
        if ($models instanceof JsonResponse) return $models; 

        $taxRateId = optional(TaxRate::where('name', $request->taxRateName)->first())->id;

        $purchaseOrder->update(array_merge(
            $request->only([
                'buyer', 'dateIssued', 'dateConfirmed', 'dateCompleted', 'dateFirstShip',
                'dateRevision', 'vendorSO', 'customerSO', 'phone', 'email', 'url', 'note',
                'deliverTo', 'paymentTermsId', 'fobPointId', 'remitToName', 'remitAddress',
                'remitCity', 'remitZip', 'shipToName', 'shipToAddress', 'shipToCity', 'shipToZip',
                'username',
            ]),
            [
                'buyerId' => $request->buyerId ?? 0,
                'locationGroupId' => $request->locationGroupId,
                'carrierId' => $models['carrier']->id,
                'currencyId' => $models['currency']->id,
                'currencyRate' => $request->currencyRate,
                'shipTermsId' => $models['shipTerms']->id,
                'remitCountryId' => $models['remitCountry']->id,
                'remitStateId' => $models['remitState']->id,
                'shipToCountryId' => $models['shipToCountry']->id,
                'shipToStateId' => $models['shipToState']->id,
                'qbClassId' => $models['qbClass']->id,
                'statusId' => $request->statusId,
                'taxRateId' => $taxRateId,
                'totalIncludesTax' => $request->totalIncludesTax,
                'totalTax' => $request->totalTax,
                'typeId' => $request->typeId,
            ]
        ));

        $purchaseOrder->items()->delete();

        $purchaseOrderItems = collect($request->validated()['items'])->map(function ($item) use ($purchaseOrder) {
            $uom = UnitOfMeasure::where('name', $item['UOM'])->firstOrFail();
            $qbClass = qbClass::where('name', $item['QuickBooksClassName'])->firstOrFail();

            return PurchaseOrderItem::create([
                'description' => $item['description'],
                'note' => $item['note'],
                'partNum' => $item['partNum'],
                'unitCost' => $item['unitCost'],
                'totalCost' => $item['totalCost'],
                'qtyToFulfill' => $item['qtyToFulfill'],
                'dateScheduledFulfillment' => $item['dateScheduledFulfillment'],
                'revLevel' => $item['revLevel'],
                'vendorPartNum' => $item['vendorPartNum'],
                'uomId' => $uom->id,
                'poId' => $purchaseOrder->id,
                'qbClassId' => $qbClass->id,
                'taxId' => $item['taxId'],
                'taxRate' => $item['taxRate'],
                'statusId' => $item['statusId'] ?? 10,
                'repairFlag' => $item['repairFlag'],
                'tbdCostFlag' => $item['tbdCostFlag'],
            ]);
        });

        return response()->json([
            'message' => 'Purchase Order updated successfully',
            'purchaseOrderData' => $purchaseOrder,
            'purchaseOrderItemData' => $purchaseOrderItems,
        ], Response::HTTP_OK);
    }

    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();
        return response()->json(['message' => 'Purchase Order deleted successfully'], Response::HTTP_OK);
    }
}
