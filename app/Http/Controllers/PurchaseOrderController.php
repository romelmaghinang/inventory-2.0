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
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\UnitOfMeasure;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

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
            $models['VendorName'] = Vendor::where('name', $request->VendorName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $newVendor = Vendor::create([
                'name' => $request->VendorName,
                'statusId' => '1'

            ]);
        
        }
        

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
    
        $poNum = $request->PONum;
    
        if ($poNum) {
            $existingOrder = PurchaseOrder::where('num', $poNum)->first();
            if ($existingOrder) {
                return response()->json(['error' => 'The Purchase Order number must be unique.'], Response::HTTP_CONFLICT);
            }
        }
    
        $prefix = '5';
        $lastOrder = PurchaseOrder::where('num', 'like', $prefix . '%')->orderBy('num', 'desc')->first();
        $newNum = $lastOrder ? intval(substr($lastOrder->num, 1)) + 1 : 10000;
        $finalNum = $poNum ?: $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);
    
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
                'num' => $finalNum,
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
                'dateCreated' => Carbon::now(),
                'dateLastModified' => Carbon::now(),
            ]
        ));
    
        $poId = $purchaseOrder->id;
    
        $purchaseOrderItems = collect($request->validated()['items'])->map(function ($item) use ($poId) {
            $uom = UnitOfMeasure::where('name', $item['UOM'])->firstOrFail();
            $qbClass = qbClass::where('name', $item['QuickBooksClassName'])->firstOrFail();
    
            $part = \App\Models\Part::where('num', $item['PartNumber'])->first();
            if (!$part) {
                throw new ModelNotFoundException("Part not found for PartNumber: {$item['PartNumber']}");
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
                'poId' => $poId,
                'qbClassId' => $qbClass->id,
                'partId' => $part->id, 
                'taxId' => '',
                'taxRate' => '',
                'statusId' => '',
                'repairFlag' => '',
                'tbdCostFlag' => '',
                'dateCreated' => Carbon::now(),
                'dateLastModified' => Carbon::now(),
            ]);
        });
    
        $receipt = Receipt::create([
            'locationGroupId' => $models['locationGroup']->id,
            'poId' => $poId,
            'orderTypeId' => 10,
            'statusId' => 10,
            'typeId' => 10,
            'userId' => 0,
            'dateCreated' => Carbon::now(),
            'dateLastModified' => Carbon::now(),
        ]);
    
        $receiptItems = $purchaseOrderItems->map(function ($poItem) use ($receipt) {
            return ReceiptItem::create([
                'receiptId' => $receipt->id,
                'poItemId' => $poItem->id,
                'partId' => $poItem->partId,
                'billVendorFlag' => 1,
                'orderTypeId' => 10,
                'statusId' => 10,
                'partTypeId' => 0,
                'typeId' => 10,
                'uomId' => 0,
                'dateCreated' => Carbon::now(),
                'dateLastModified' => Carbon::now(),
            ]);
        });
    
        return response()->json([
            'message' => 'Purchase Order successfully created',
            'purchaseOrderData' => $purchaseOrder,
            'purchaseOrderItemData' => $purchaseOrderItems,
            'receiptData' => $receipt,
            'receiptItemData' => $receiptItems,
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
                'dateLastModified' => Carbon::now(), 

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
                'dateCreated' => Carbon::now(),  
                'dateLastModified' => Carbon::now(), 
                
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
