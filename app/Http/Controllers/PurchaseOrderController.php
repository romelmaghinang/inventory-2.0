<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Postatus;
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
use Illuminate\Http\Request;

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
    /**
 * @OA\Post(
 *     path="api/purchase-order",
 *     summary="Create a new Purchase Order",
 *     description="Creates a new Purchase Order with items and returns the created data.",
 *     tags={"Purchase Order"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="PONum", type="string", description="Purchase Order Number"),
 *             @OA\Property(property="buyerId", type="integer", description="ID of the buyer"),
 *             @OA\Property(property="dateIssued", type="string", format="date", description="Issued date of the Purchase Order"),
 *             @OA\Property(property="taxRateName", type="string", description="Tax rate associated with the Purchase Order"),
 *             @OA\Property(property="items", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="PartNumber", type="string", description="Part number"),
 *                     @OA\Property(property="UOM", type="string", description="Unit of Measure"),
 *                     @OA\Property(property="VendorPartNumber", type="string", description="Vendor part number"),
 *                     @OA\Property(property="FulfillmentDate", type="string", format="date", description="Scheduled fulfillment date")
 *                 )
 *             ),
 *             @OA\Property(property="currencyRate", type="number", format="float", description="Currency rate"),
 *             @OA\Property(property="statusId", type="integer", description="Purchase Order status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Purchase Order successfully created",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Response message"),
 *             @OA\Property(property="purchaseOrderData", type="object", description="Created Purchase Order data"),
 *             @OA\Property(property="purchaseOrderItemData", type="array", description="Items associated with the Purchase Order",
 *                 @OA\Items(
 *                     @OA\Property(property="partNum", type="string", description="Part number"),
 *                     @OA\Property(property="qtyToFulfill", type="integer", description="Quantity to fulfill")
 *                 )
 *             ),
 *             @OA\Property(property="receiptData", type="object", description="Receipt data"),
 *             @OA\Property(property="receiptItemData", type="array", description="Receipt items",
 *                 @OA\Items(
 *                     @OA\Property(property="receiptId", type="integer", description="Receipt ID")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Conflict: Purchase Order number must be unique",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", description="Error message")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Validation error message")
 *         )
 *     )
 * )
 */


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
            'relatedData' => [
                'locationGroup' => $models['locationGroup'],
                'carrier' => $models['carrier'],
                'currency' => $models['currency'],
                'shipTerms' => $models['shipTerms'],
                'remitCountry' => $models['remitCountry'],
                'remitState' => $models['remitState'],
                'shipToCountry' => $models['shipToCountry'],
                'shipToState' => $models['shipToState'],
                'qbClass' => $models['qbClass'],
                'taxRate' => $taxRateId ? TaxRate::find($taxRateId) : null
            ]
        ], Response::HTTP_CREATED);
    }

    
    /**
     * @OA\Get(
     *     path="/api/purchase-order",
     *     summary="Retrieve Purchase Orders",
     *     description="Fetches the details of a specific Purchase Order by `num` from the JSON request body or query parameters. If no `num` is provided, it fetches all Purchase Orders or filters by date range using createdBefore and createdAfter.",
     *     tags={"Purchase Order"},
     *     @OA\Parameter(
     *         name="num",
     *         in="query",
     *         description="Purchase Order Number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="createdBefore",
     *         in="query",
     *         description="Retrieve Purchase Orders created before this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="createdAfter",
     *         in="query",
     *         description="Retrieve Purchase Orders created after this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="num", type="integer", description="Purchase Order Number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object", description="Purchase order details or list of all purchase orders")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Purchase Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid request")
     *         )
     *     )
     * )
     */

     public function show(Request $request): JsonResponse
     {
         $num = $request->json('num');
         $status = $request->json('status');
         $createdBefore = $request->input('createdBefore'); 
         $createdAfter = $request->input('createdAfter'); 
         $page = $request->input('page', 1);
         $perPage = 100;
     
         $id = $request->input('id');
     
         if ($id) {
             $request->validate([
                 'id' => 'required|integer|exists:po,id',
             ]);
     
             $purchaseOrder = PurchaseOrder::find($id);
     
             if (!$purchaseOrder) {
                 return response()->json(['message' => 'Purchase Order not found'], Response::HTTP_NOT_FOUND);
             }
     
             return response()->json(['success' => true, 'data' => $purchaseOrder], Response::HTTP_OK);
         }
     
         if ($num) {
             $request->validate([
                 'num' => 'required|integer|exists:po,num',
             ]);
     
             $purchaseOrder = PurchaseOrder::where('num', $num)->first();
     
             if (!$purchaseOrder) {
                 return response()->json(['message' => 'Purchase Order not found'], Response::HTTP_NOT_FOUND);
             }
     
             return response()->json(['success' => true, 'data' => $purchaseOrder], Response::HTTP_OK);
         }
     
         $query = PurchaseOrder::query();
     
         if ($createdBefore) {
             $request->validate([
                 'createdBefore' => 'date|before_or_equal:today',
             ]);
             $query->whereDate('dateCreated', '<=', $createdBefore);
         }
     
         if ($createdAfter) {
             $request->validate([
                 'createdAfter' => 'date|before_or_equal:today',
             ]);
             $query->whereDate('dateCreated', '>=', $createdAfter);
         }
     
         if ($status) {
             $request->validate([
                 'status' => 'string|exists:postatus,name',
             ]);
     
             $statusId = Postatus::where('name', $status)->value('id');
             if ($statusId) {
                 $query->where('statusId', $statusId);
             }
         }
     
         $purchaseOrders = $query->paginate($perPage, ['*'], 'page', $page);
     
         return response()->json([
             'success' => true,
             'data' => $purchaseOrders
         ], Response::HTTP_OK);
     }
     
     
     
 
     
    /**
     * @OA\Put(
     *     path="/api/purchase-order",
     *     summary="Update a specific Purchase Order",
     *     description="Updates the details of a specific Purchase Order by `poId` from the JSON request.",
     *     tags={"Purchase Order"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="poId", type="integer", description="Purchase Order ID"),
     *             @OA\Property(property="status", type="string", description="New status of the purchase order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful update",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase Order not found"
     *     )
     * )
     */
    public function update(UpdatePurchaseOrderRequest $request, $id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::find($id);
        
        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase Order not found'], Response::HTTP_NOT_FOUND);
        }
        
        $purchaseOrder->status = $request->input('status');
        $purchaseOrder->save();
    
        $relatedData = [
            'locationGroup' => $purchaseOrder->locationGroup,
            'carrier' => $purchaseOrder->carrier,
            'currency' => $purchaseOrder->currency,
            'shipTerms' => $purchaseOrder->shipTerms,
            'remitCountry' => $purchaseOrder->remitCountry,
            'remitState' => $purchaseOrder->remitState,
            'shipToCountry' => $purchaseOrder->shipToCountry,
            'shipToState' => $purchaseOrder->shipToState,
            'qbClass' => $purchaseOrder->qbClass,
            'taxRate' => $purchaseOrder->taxRate,
            'purchaseOrderItems' => $purchaseOrder->items,
            'receipt' => $purchaseOrder->receipt,
            'receiptItems' => $purchaseOrder->receipt->items,
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'Purchase Order updated successfully',
            'purchaseOrderData' => $purchaseOrder,
            'relatedData' => $relatedData
        ], Response::HTTP_OK);
    }
    
    

    /**
     * @OA\Delete(
     *     path="/api/purchase-order",
     *     summary="Delete a specific Purchase Order",
     *     description="Deletes a specific Purchase Order by `poId` from the JSON request.",
     *     tags={"Purchase Order"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="poId", type="integer", description="Purchase Order ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful deletion",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase Order not found"
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $poId = $request->input('poId');
        $purchaseOrder = PurchaseOrder::find($poId);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase Order not found'], Response::HTTP_NOT_FOUND);
        }

        $purchaseOrder->delete();

        return response()->json(['success' => true, 'message' => 'Purchase Order deleted successfully'], Response::HTTP_OK);
    }
}
