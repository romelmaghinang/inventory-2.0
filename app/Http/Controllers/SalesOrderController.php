<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesOrder\StoreSalesOrderRequest;
use App\Http\Requests\SalesOrder\UpdateSalesOrderRequest;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\LocationGroup;
use App\Models\Pick;
use App\Models\Product;
use App\Models\qbClass;
use App\Models\SalesOrder;
use App\Models\SalesOrderItems;
use App\Models\ShipTerms;
use App\Models\State;
use App\Models\TaxRate;
use App\Models\UnitOfMeasure;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/sales-order",
 *     summary="Create a new Sales Order",
 *     tags={"Sales Order"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="soNum", type="integer", example=10001),
 *             @OA\Property(property="customerName", type="string", example="John Doe"),
 *             @OA\Property(property="customerContact", type="string", example="Jane Smith"),
 *             @OA\Property(property="billToName", type="string", example="John Doe"),
 *             @OA\Property(property="billToAddress", type="string", example="123 Main St"),
 *             @OA\Property(property="billToCity", type="string", example="Springfield"),
 *             @OA\Property(property="billToState", type="string", example="California"),
 *             @OA\Property(property="billToZip", type="string", example="62701"),
 *             @OA\Property(property="billToCountry", type="string", example="United States"),
 *             @OA\Property(property="shipToName", type="string", example="John Doe"),
 *             @OA\Property(property="shipToAddress", type="string", example="123 Main St"),
 *             @OA\Property(property="shipToCity", type="string", example="Springfield"),
 *             @OA\Property(property="shipToState", type="string", example="California"),
 *             @OA\Property(property="shipToZip", type="string", example="62701"),
 *             @OA\Property(property="shipToCountry", type="string", example="United States"),
 *             @OA\Property(property="shipToResidential", type="boolean", example=true),
 *             @OA\Property(property="carrierName", type="string", example="Delivery"),
 *             @OA\Property(property="carrierService", type="string", example="Ground"),
 *             @OA\Property(property="taxRateName", type="string", example="Standard Tax"),
 *             @OA\Property(property="priorityId", type="integer", example=10),
 *             @OA\Property(property="poNum", type="string", example="PO123456"),
 *             @OA\Property(property="vendorPONum", type="string", example="VendorPO123"),
 *             @OA\Property(property="date", type="string", format="date", example="2024-08-23"),
 *             @OA\Property(property="orderDateScheduled", type="string", format="date", example="2024-08-25"),
 *             @OA\Property(property="dateExpired", type="string", format="date", example="2024-09-01"),
 *             @OA\Property(property="salesman", type="string", example="Salesperson A"),
 *             @OA\Property(property="shippingTerms", type="string", example="Prepaid"),
 *             @OA\Property(property="paymentTerms", type="string", example="Net 30"),
 *             @OA\Property(property="fob", type="string", example="FOB Destination"),
 *             @OA\Property(property="note", type="string", example="This is a note"),
 *             @OA\Property(property="quickBookClassName", type="string", example="Sales"),
 *             @OA\Property(property="locationGroupName", type="string", example="Main"),
 *             @OA\Property(property="phone", type="string", example="+1-555-555-5555"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *             @OA\Property(property="url", type="string", example="http://example.com"),
 *             @OA\Property(property="category", type="string", example="Electronics"),
 *             @OA\Property(property="customField", type="string", example="CustomValue"),
 *             @OA\Property(property="currencyName", type="string", example="US Dollar"),
 *             @OA\Property(property="currencyRate", type="number", format="float", example=1.0),
 *             @OA\Property(property="priceIsHomeCurrency", type="number", format="float", example=100.0),
 *             @OA\Property(
 *                 property="items",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="soItemTypeId", type="integer", example=10),
 *                     @OA\Property(property="productNumber", type="string", example="CK100"),
 *                     @OA\Property(property="productDescription", type="string", example="Product Description"),
 *                     @OA\Property(property="productQuantity", type="integer", example=10),
 *                     @OA\Property(property="uom", type="string", example="Foot"),
 *                     @OA\Property(property="productPrice", type="number", format="float", example=50.0),
 *                     @OA\Property(property="taxable", type="boolean", example=true),
 *                     @OA\Property(property="taxCode", type="integer", example=1),
 *                     @OA\Property(property="note", type="string", example="Item Note"),
 *                     @OA\Property(property="itemQuickBooksClassName", type="string", example="Sales"),
 *                     @OA\Property(property="itemDateScheduled", type="string", format="date", example="2024-08-30"),
 *                     @OA\Property(property="showItem", type="boolean", example=true),
 *                     @OA\Property(property="revisionLevel", type="string", example="Rev1"),
 *                     @OA\Property(property="customerPartNumber", type="string", example="CustPart123"),
 *                     @OA\Property(property="kitItem", type="boolean", example=false),
 *                     @OA\Property(property="cfi", type="string", example="CFIValue")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Sales Order created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Sales Order created successfully"),

 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="The Sales Order number must be unique",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     )
 * )
 */
    public function store(StoreSalesOrderRequest $storeSalesOrderRequest): JsonResponse
    {
        $billToCountry = Country::where('name', $storeSalesOrderRequest->billToCountry)->firstOrFail();
        $billToState = State::where('name', $storeSalesOrderRequest->billToState)->firstOrFail();
        $shipToCountry = Country::where('name', $storeSalesOrderRequest->shipToCountry)->firstOrFail();
        $shipToState = State::where('name', $storeSalesOrderRequest->shipToState)->firstOrFail();
        $qbclass = qbClass::where('name', $storeSalesOrderRequest->quickBookClassName)->firstOrFail();
        $currency = Currency::where('name', $storeSalesOrderRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $storeSalesOrderRequest->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $storeSalesOrderRequest->carrierService)->firstOrFail();
        $taxRate = TaxRate::where('name', $storeSalesOrderRequest->taxRateName)->firstOrFail();
        $shipterms = ShipTerms::where('name', $storeSalesOrderRequest->shippingTerms)->firstOrFail();
    
        $customer = Customer::firstOrCreate(['name' => $storeSalesOrderRequest->customerName]);
    
        $soNum = $storeSalesOrderRequest->soNum;
    
        if (!empty($soNum)) {
            if (SalesOrder::where('num', $soNum)->exists()) {
                return response()->json(['message' => 'The Sales Order number must be unique.'], Response::HTTP_CONFLICT);
            }
            $newNum = $soNum;
        } else {
            $lastSalesOrder = SalesOrder::orderBy('num', 'desc')->first();
            $nextNum = (string)((optional($lastSalesOrder)->num ?? 10000) + 1); 
    
            while (SalesOrder::where('num', $nextNum)->exists()) {
                $nextNum = (string)(intval($nextNum) + 1); 
            }
            $newNum = $nextNum;
        }
    
        $locationGroup = LocationGroup::where('name', $storeSalesOrderRequest->locationGroupName)->firstOrFail();
    
        $salesOrder = SalesOrder::create(
            $storeSalesOrderRequest->only([
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
                'priorityId',
                'paymentTerms',
                'fob',
                'note',
                'phone',
                'email',
                'url',
                'category',
                'customField',
                'currencyRate',
            ]) + [
                'locationGroupId' => $locationGroup->id,
                'activeFlag' => $storeSalesOrderRequest->flag,
                'shipTermsId' => $shipterms->id,
                'billToCountryId' => $billToCountry->id,
                'billToStateId' => $billToState->id,
                'shipToCountryId' => $shipToCountry->id,
                'shipToStateId' => $shipToState->id,
                'taxRateId' => $taxRate->id,
                'statusId' => $storeSalesOrderRequest->status ?? 20,
                'currencyId' => $currency->id,
                'customerId' => $customer->id,
                'carrierId' => $carrier->id,
                'carrierServiceId' => $carrierService->id,
                'residentialFlag' => $storeSalesOrderRequest->shipToResidential,
                'qbClassId' => $qbclass->id,
                'num' => $newNum,
            ]
        );
    
        $salesOrderItems = [];
    
        foreach ($storeSalesOrderRequest->validated()['items'] as $item) {
            $product = Product::where('num', $item['productNumber'])->firstOrFail();
            $qbClass = qbClass::where('name', $item['itemQuickBooksClassName'])->firstOrFail();
            $uom = UnitOfMeasure::where('name', $item['uom'])->firstOrFail();
    
            $transformedItem = [
                'note' => $item['note'],
                'typeId' => $item['soItemTypeId'],
                'uomId' => $uom->id,
                'productId' => $product->id,
                'productNum' => $item['productNumber'],
                'showItemFlag' => $item['showItem'],
                'taxRateCode' => $item['taxCode'],
                'taxableFlag' => $item['taxable'],
                'customerPartNum' => $item['customerPartNumber'],
                'description' => $item['productDescription'],
                'qtyOrdered' => $item['productQuantity'],
                'unitPrice' => $item['productPrice'],
                'dateScheduledFulfillment' => $item['itemDateScheduled'],
                'revLevel' => $item['revisionLevel'],
                'customFieldItem' => $item['cfi'],
                'soId' => $salesOrder->id,
                'qbClassId' => $qbClass->id,
                'statusId' => 10,
            ];
    
            $salesOrderItems[] = SalesOrderItems::create($transformedItem);
        }
    
        $pick = Pick::create(
            [
                'num' => $salesOrder->num,
                'locationGroupId' => $locationGroup->id,
            ]
        );
    
        return response()->json(
            [
                'message' => 'Sales Order created successfully',
                'salesOrderData' => $salesOrder,
                'salesOrderItemData' => $salesOrderItems,
                'pick' => $pick,
            ],
            Response::HTTP_CREATED
        );
    }
    
    
    /**
     * @OA\Get(
     *     path="/api/sales-order",
     *     tags={"Sales Order"},
     *     summary="Retrieve sales orders",
     *     description="Fetches a specific sales order by number from either query parameters or request body, or filters sales orders by date range using createdBefore and createdAfter.",
     *     @OA\Parameter(
     *         name="num",
     *         in="query",
     *         description="The sales order number to retrieve",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="createdBefore",
     *         in="query",
     *         description="Retrieve sales orders created before this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="createdAfter",
     *         in="query",
     *         description="Retrieve sales orders created after this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="num", type="integer", description="The sales order number to retrieve", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of sales orders.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sales Order not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sales Order not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid request.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred.")
     *         )
     *     )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $numFromQuery = $request->input('num');
        $numFromBody = $request->json('num');

        $createdBefore = $request->input('createdBefore');
        $createdAfter = $request->input('createdAfter');

        $num = $numFromQuery ?? $numFromBody;

        if ($num) {
            $request->validate([
                'num' => 'required|integer|exists:so,num',
            ]);

            $salesOrder = SalesOrder::where('num', $num)->first();

            if (!$salesOrder) {
                return response()->json(['message' => 'Sales Order not found.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($salesOrder, Response::HTTP_OK);
        }

        $query = SalesOrder::query();

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

        $salesOrders = $query->get();

        return response()->json($salesOrders, Response::HTTP_OK);
    }


/**
 * @OA\Put(
 *     path="/api/sales-order",
 *     summary="Update a sales order",
 *     tags={"Sales Order"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="soId", type="integer", example=1),
 *             @OA\Property(property="customerContact", type="string", nullable=true),
 *             @OA\Property(property="billToName", type="string", nullable=true),
 *             @OA\Property(property="billToAddress", type="string", nullable=true),
 *             @OA\Property(property="billToCity", type="string", nullable=true),
 *             @OA\Property(property="billToZip", type="string", nullable=true),
 *             @OA\Property(property="shipToName", type="string", nullable=true),
 *             @OA\Property(property="shipToAddress", type="string", nullable=true),
 *             @OA\Property(property="shipToCity", type="string", nullable=true),
 *             @OA\Property(property="shipToZip", type="string", nullable=true),
 *             @OA\Property(property="vendorPONum", type="string", nullable=true),
 *             @OA\Property(property="date", type="string", format="date", nullable=true),
 *             @OA\Property(property="dateExpired", type="string", format="date", nullable=true),
 *             @OA\Property(property="salesman", type="string", nullable=true),
 *             @OA\Property(property="priorityId", type="integer", nullable=true),
 *             @OA\Property(property="paymentTerms", type="string", nullable=true),
 *             @OA\Property(property="fob", type="string", nullable=true),
 *             @OA\Property(property="note", type="string", nullable=true),
 *             @OA\Property(property="locationGroupName", type="string", nullable=true),
 *             @OA\Property(property="phone", type="string", nullable=true),
 *             @OA\Property(property="email", type="string", format="email", nullable=true),
 *             @OA\Property(property="url", type="string", format="url", nullable=true),
 *             @OA\Property(property="category", type="string", nullable=true),
 *             @OA\Property(property="customField", type="string", nullable=true),
 *             @OA\Property(property="currencyRate", type="number", format="float", nullable=true),
 *             @OA\Property(property="status", type="integer", nullable=true),
 *             @OA\Property(property="items", type="array", @OA\Items(
 *                 @OA\Property(property="note", type="string", nullable=true),
 *                 @OA\Property(property="soItemTypeId", type="integer", nullable=true),
 *                 @OA\Property(property="uom", type="integer", nullable=true),
 *                 @OA\Property(property="productNumber", type="string", nullable=false),
 *                 @OA\Property(property="showItem", type="boolean", nullable=true),
 *                 @OA\Property(property="taxCode", type="string", nullable=true),
 *                 @OA\Property(property="taxable", type="boolean", nullable=true),
 *                 @OA\Property(property="customerPartNumber", type="string", nullable=true),
 *                 @OA\Property(property="productDescription", type="string", nullable=true),
 *                 @OA\Property(property="productQuantity", type="number", format="float", nullable=false),
 *                 @OA\Property(property="productPrice", type="number", format="float", nullable=false),
 *                 @OA\Property(property="itemDateScheduled", type="string", format="date", nullable=true),
 *                 @OA\Property(property="revisionLevel", type="integer", nullable=true),
 *                 @OA\Property(property="cfi", type="string", nullable=true),
 *                 @OA\Property(property="itemQuickBooksClassName", type="string", nullable=true)
 *             )),
 *             @OA\Property(property="billToCountry", type="string", nullable=false),
 *             @OA\Property(property="billToState", type="string", nullable=false),
 *             @OA\Property(property="shipToCountry", type="string", nullable=false),
 *             @OA\Property(property="shipToState", type="string", nullable=false),
 *             @OA\Property(property="quickBookClassName", type="string", nullable=false),
 *             @OA\Property(property="currencyName", type="string", nullable=false),
 *             @OA\Property(property="carrierName", type="string", nullable=false),
 *             @OA\Property(property="carrierService", type="string", nullable=false),
 *             @OA\Property(property="taxRateName", type="string", nullable=false),
 *             @OA\Property(property="shippingTerms", type="string", nullable=false),
 *             @OA\Property(property="shipToResidential", type="boolean", nullable=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sales order updated successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Sales Order updated successfully"),
 *             @OA\Property(property="salesOrderData", type="object"),
 *             @OA\Property(property="salesOrderItemData", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(response=404, description="Sales order not found"),
 *     @OA\Response(response=422, description="Validation errors")
 * )
 */
    public function update(UpdateSalesOrderRequest $updateSalesOrderRequest): JsonResponse
    {
        $billToCountry = Country::where('name', $updateSalesOrderRequest->billToCountry)->firstOrFail();
        $billToState = State::where('name', $updateSalesOrderRequest->billToState)->firstOrFail();
        $shipToCountry = Country::where('name', $updateSalesOrderRequest->shipToCountry)->firstOrFail();
        $shipToState = State::where('name', $updateSalesOrderRequest->shipToState)->firstOrFail();
        $qbclass = qbClass::where('name', $updateSalesOrderRequest->quickBookClassName)->firstOrFail();
        $currency = Currency::where('name', $updateSalesOrderRequest->currencyName)->firstOrFail();
        $carrier = Carrier::where('name', $updateSalesOrderRequest->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $updateSalesOrderRequest->carrierService)->firstOrFail();
        $taxRate = TaxRate::where('name', $updateSalesOrderRequest->taxRateName)->firstOrFail();
        $shipterms = ShipTerms::where('name', $updateSalesOrderRequest->shippingTerms)->firstOrFail();

        $customer = Customer::firstOrCreate(['name' => $updateSalesOrderRequest->customerName]);

        $salesOrder = SalesOrder::findOrFail($updateSalesOrderRequest->soId); 

        $salesOrder->update(
            $updateSalesOrderRequest->only([
                'customerContact',
                'billToName',
                'billToAddress',
                'billToCity',
                'billToZip',
                'shipToName',
                'shipToAddress',
                'shipToCity',
                'shipToZip',
                'vendorPONum',
                'date',
                'dateExpired',
                'salesman',
                'priorityId',
                'paymentTerms',
                'fob',
                'note',
                'locationGroupName',
                'phone',
                'email',
                'url',
                'category',
                'customField',
                'currencyRate',
            ]) + [
                'activeFlag' => $updateSalesOrderRequest->flag,
                'shipTermsId' => $shipterms->id,
                'billToCountryId' => $billToCountry->id,
                'billToStateId' => $billToState->id,
                'shipToCountryId' => $shipToCountry->id,
                'shipToStateId' => $shipToState->id,
                'taxRateId' => $taxRate->id,
                'statusId' => $updateSalesOrderRequest->status,
                'currencyId' => $currency->id,
                'customerId' => $customer->id,
                'carrierId' => $carrier->id,
                'carrierServiceId' => $carrierService->id,
                'residentialFlag' => $updateSalesOrderRequest->shipToResidential,
                'qbClassId' => $qbclass->id,
            ]
        );

        $salesOrder->items()->delete();

        $salesOrderItems = [];
        foreach ($updateSalesOrderRequest->validated()['items'] as $item) {
            $product = Product::where('num', $item['productNumber'])->firstOrFail();
            $qbClass = qbClass::firstOrCreate(['name' => $item['itemQuickBooksClassName']]);

            $transformedItem = [
                'note' => $item['note'],
                'typeId' => $item['soItemTypeId'],
                'oumId' => $item['uom'],
                'productId' => $product->id,
                'productNum' => $item['productNumber'],
                'showItemFlag' => $item['showItem'],
                'taxRateCode' => $item['taxCode'],
                'taxableFlag' => $item['taxable'],
                'customerPartNum' => $item['customerPartNumber'],
                'description' => $item['productDescription'],
                'qtyOrdered' => $item['productQuantity'],
                'unitPrice' => $item['productPrice'],
                'dateScheduledFulfillment' => $item['itemDateScheduled'],
                'revLevel' => $item['revisionLevel'],
                'customFieldItem' => $item['cfi'],
                'soId' => $salesOrder->id,
                'qbClassId' => $qbClass->id,
                'statusId' => $updateSalesOrderRequest->status ?? 20,
            ];

            $salesOrderItems[] = SalesOrderItems::create($transformedItem);
        }

        return response()->json(
            [
                'message' => 'Sales Order updated successfully',
                'salesOrderData' => $salesOrder,
                'salesOrderItemData' => $salesOrderItems,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/sales-order",
     *     tags={"Sales Order"},
     *     summary="Delete a sales order",
     *     description="Delete an existing sales order from the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"soId"},
     *             @OA\Property(property="soId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sales Order deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sales Order Deleted Successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sales Order not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sales Order not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message.")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'soId' => 'required|integer|exists:so,id',
        ]);

        $salesOrder = SalesOrder::findOrFail($request->soId);
        $salesOrder->delete();

        return response()->json(
            [
                'message' => 'Sales Order Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
