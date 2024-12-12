<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Xo;
use App\Models\State;
use App\Models\Pick;
use App\Models\Location;
use App\Models\XoItemType;
use App\Models\XoItem;
use App\Models\Part;
use App\Models\UnitOfMeasure;
use App\Models\Address;
use App\Models\Country;
use App\Models\LocationGroup;
use App\Models\XoItemStatus;
use App\Models\XoStatus;
use App\Models\Carrier;
use App\Models\XoType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\TransferOrder\StoreTransferOrderRequest;
use App\Http\Requests\TransferOrder\UpdateTransferOrderRequest;

class TransferOrderController extends Controller
{
    public function store(StoreTransferOrderRequest $request): JsonResponse
    {
        $data = $request->validate([
            'TO' => 'required|array',
            'TO.TONum' => 'nullable|string',
            'TO.TOType' => 'required|string',
            'TO.Status' => 'nullable|string',
            'TO.FromLocationGroup' => 'required|string',
            'TO.FromAddressName' => 'required|string',
            'TO.FromAddressStreet' => 'required|string',
            'TO.FromAddressCity' => 'required|string',
            'TO.FromAddressZip' => 'required|string',
            'TO.FromAddressCountry' => 'required|string',
            'TO.ToLocationGroup' => 'required|string',
            'TO.ToAddressName' => 'required|string',
            'TO.ToAddressStreet' => 'required|string',
            'TO.ToAddressCity' => 'required|string',
            'TO.ToAddressZip' => 'required|string',
            'TO.ToAddressCountry' => 'required|string',
            'TO.OwnerIsFrom' => 'required|string',
            'TO.CarrierName' => 'required|string',
            'TO.CarrierService' => 'nullable|string',
            'TO.Note' => 'nullable|string',
            'TO.CF' => 'nullable|string',
            'Items' => 'required|array',
            'Items.*.PartNumber' => 'required|string',
            'Items.*.PartQuantity' => 'required|integer',
            'Items.*.UOM' => 'required|string',
            'Items.*.Note' => 'nullable|string',
        ]);


        try {
            $status = XoItemStatus::where('name', 'Entered')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Status not found',
                'message' => "The status '{$data['TO']['Status']}' was not found.",
            ], 404);
        }

        try {
            $fromLocationGroup = LocationGroup::where('name', $data['TO']['FromLocationGroup'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Location Group not found',
                'message' => "The location group '{$data['TO']['FromLocationGroup']}' was not found.",
            ], 404);
        }

        try {
            $toType = XoType::where('name', $data['TO']['TOType'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Transfer Order Type not found',
                'message' => "The Transfer Order type '{$data['TO']['TOType']}' was not found.",
            ], 404);
        }

        try {
            $carrier = Carrier::where('name', $data['TO']['CarrierName'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Carrier not found',
                'message' => "The carrier '{$data['TO']['CarrierName']}' was not found.",
            ], 404);
        }

        try {
            $country = Country::where('abbreviation', $data['TO']['FromAddressCountry'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Country not found',
                'message' => "The country abbreviation '{$data['TO']['FromAddressCountry']}' was not found.",
            ], 404);
        }


        try {
            $toLocationGroup = LocationGroup::where('name', $data['TO']['ToLocationGroup'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'To Location Group not found',
                'message' => "The location group '{$data['TO']['ToLocationGroup']}' was not found.",
            ], 404);
        }
        
        $fromAddress = Address::firstOrCreate([
            'name' => 'New Address',
            'addressName' => $data['TO']['FromAddressName'],
            'address' => $data['TO']['FromAddressStreet'],
            'city' => $data['TO']['FromAddressCity'],
            'zip' => $data['TO']['FromAddressZip'],
            'countryId' => $country->id,
            'locationGroupId' =>$toLocationGroup->id
        ]);
        if (empty($data['TO']['TONum'])) {
            $lastTonum = Xo::orderBy('num', 'desc')->first(); 
        
            if ($lastTonum) {
                $nextNum = (int) $lastTonum->num + 1;
            } else {
                $nextNum = 1; 
            }
        
            while (Xo::where('num', $nextNum)->exists()) {
                $nextNum++; 
            }
        
            $data['TO']['TONum'] = $nextNum; 
        }
        $xo = Xo::create([
            'num' => $data['TO']['TONum'],
            'statusId' => $status->id,
            'typeId' => $toType->id,
            'carrierId' => $carrier->id,
            'fromLGId' => $fromLocationGroup->id,
            'shipToLGId' => $toLocationGroup->id,
            'fromAddress' => $data['TO']['FromAddressStreet'],
            'fromCity' => $data['TO']['FromAddressCity'],
            'fromZip' => $data['TO']['FromAddressZip'],
            'fromCountryId' => $country->id,
            'shipToAddress' => $data['TO']['ToAddressStreet'],
            'shipToCity' => $data['TO']['ToAddressCity'],
            'shipToZip' => $data['TO']['ToAddressZip'],
            'shipToCountryId' => Country::where('abbreviation', $data['TO']['ToAddressCountry'])->value('id'),
            'ownerIsFrom' => $data['TO']['OwnerIsFrom'] === 'true',
            'dateCreated' => Carbon::now(),
            'dateIssued' => Carbon::now(),
            'note' => $data['TO']['Note'] ?? null,
            'mainLocationTagId' => '0',
            'userId' => '0'
        ]);
        $pick = Pick::create([
            'num' => $data['TO']['TONum'],
            'locationGroupId' => $fromLocationGroup->id,
        ]);

        foreach ($data['Items'] as $item) {
            try {
                $part = Part::where('num', $item['PartNumber'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Part not found',
                    'message' => "The part number '{$item['PartNumber']}' was not found.",
                ], 404);
            }

            try {
                $uom = UnitOfMeasure::where('name', $item['UOM'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Unit of Measure not found',
                    'message' => "The UOM '{$item['UOM']}' was not found.",
                ], 404);
            }

            $xoItem = XoItem::create([
                'xoId' => $xo->id,
                'partId' => $part->id,
                'partNum' => $part->num,
                'lineItem' => 1,
                'statusId' => $status->id,
                'uomId' => $uom->id,
                'qtyToFulfill' => $item['PartQuantity'],
                'qtyPicked' => 0,
                'qtyFulfilled' => 0,
                'description' => $item['Note'],
                'note' => $item['Note'] ?? null,
                'typeId' => $toType->id
            ]);

            switch ('Entered') {
                case 'Fulfilled':
                    $xoItem->update(['qtyFulfilled' => $xoItem->qtyToFulfill]);
                    break;
                case 'Entered':
                    $xoItem->update(['qtyToFulfill' => $item['PartQuantity']]);
                    break;
                case 'Picked':
                    $xoItem->update(['qtyPicked' => $item['PartQuantity']]);
                    break;
            }


        }
        $relatedData = [
            'status' => $status,
            'fromLocationGroup' => $fromLocationGroup,
            'toType' => $toType,
            'carrier' => $carrier,
            'country' => $country,
            'toLocationGroup' => $toLocationGroup,
            'pickData' => $pick,

        ];

        return response()->json([
            'message' => 'Transfer Order successfully created.',
            'xo' => $xo,
            'xoItems' => $xoItem,
            'relatedData' => $relatedData,
        ], 201);
    }
    public function update(UpdateTransferOrderRequest $request, $id): JsonResponse
    {
        $data = $request->validate([
            'TO.TONum' => 'nullable|string',
            'TO.TOType' => 'nullable|string',
            'TO.Status' => 'nullable|string',
            'TO.FromLocationGroup' => 'nullable|string',
            'TO.FromAddressName' => 'nullable|string',
            'TO.FromAddressStreet' => 'nullable|string',
            'TO.FromAddressCity' => 'nullable|string',
            'TO.FromAddressZip' => 'nullable|string',
            'TO.FromAddressCountry' => 'nullable|string',
            'TO.ToLocationGroup' => 'nullable|string',
            'TO.ToAddressName' => 'nullable|string',
            'TO.ToAddressStreet' => 'nullable|string',
            'TO.ToAddressCity' => 'nullable|string',
            'TO.ToAddressZip' => 'nullable|string',
            'TO.ToAddressCountry' => 'nullable|string',
            'TO.OwnerIsFrom' => 'nullable|string',
            'TO.CarrierName' => 'nullable|string',
            'TO.CarrierService' => 'nullable|string',
            'TO.Note' => 'nullable|string',
            'TO.CF' => 'nullable|string',
        ]);
    
        try {
            $xo = Xo::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Transfer Order not found',
                'message' => "The Transfer Order with ID '{$id}' was not found.",
            ], 404);
        }
    
        $updatedFields = [];
    
        if (isset($data['TO'])) {
            foreach ($data['TO'] as $key => $value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'Status':
                            $status = XoStatus::where('name', $value)->first();
                            if ($status) {
                                $updatedFields['statusId'] = $status->id;
                            }
                            break;
    
                        case 'TOType':
                            $toType = XoType::where('name', $value)->first();
                            if ($toType) {
                                $updatedFields['typeId'] = $toType->id;
                            }
                            break;
    
                        case 'CarrierName':
                            $carrier = Carrier::where('name', $value)->first();
                            if ($carrier) {
                                $updatedFields['carrierId'] = $carrier->id;
                            }
                            break;
    
                        case 'FromLocationGroup':
                            $fromLocationGroup = LocationGroup::where('name', $value)->first();
                            if ($fromLocationGroup) {
                                $updatedFields['fromLGId'] = $fromLocationGroup->id;
                            }
                            break;
    
                        case 'ToLocationGroup':
                            $toLocationGroup = LocationGroup::where('name', $value)->first();
                            if ($toLocationGroup) {
                                $updatedFields['shipToLGId'] = $toLocationGroup->id;
                            }
                            break;
    
                        case 'FromAddressStreet':
                            $updatedFields['fromAddress'] = $value;
                            break;
    
                        case 'FromAddressCity':
                            $updatedFields['fromCity'] = $value;
                            break;
    
                        case 'FromAddressZip':
                            $updatedFields['fromZip'] = $value;
                            break;
    
                        case 'ToAddressStreet':
                            $updatedFields['shipToAddress'] = $value;
                            break;
    
                        case 'ToAddressCity':
                            $updatedFields['shipToCity'] = $value;
                            break;
    
                        case 'ToAddressZip':
                            $updatedFields['shipToZip'] = $value;
                            break;
    
                        default:
                            $updatedFields[$key] = $value;
                            break;
                    }
                }
            }
    
            $xo->update($updatedFields);
        }
    
        $relatedData = array_filter([
            'status' => $status ?? null,
            'fromLocationGroup' => $fromLocationGroup ?? null,
            'toType' => $toType ?? null,
            'carrier' => $carrier ?? null,
            'toLocationGroup' => $toLocationGroup ?? null,
        ]);
    
        return response()->json([
            'message' => 'Transfer Order updated successfully.',
            'xo' => $xo,
            'relatedData' => $relatedData,
        ], 200);
    }
    


    public function showXo(Request $request, $xoId = null): JsonResponse
    {
        $perPage = $request->input('perPage', 100);
        $page = $request->input('page', 1);
        $type = $request->input('type'); 
    
        $query = Xo::query();
    
        if ($xoId) {
            $query->where('id', $xoId);
        }
    
        if ($type) {
            $typeId = XoType::where('name', $type)->value('id');
            if ($typeId) {
                $query->where('typeId', $typeId);
            }
        }
    
        $picks = $query->paginate($perPage, ['*'], 'page', $page);
    
        $data = collect($picks->items())->map(function ($xo) {
            return [
                'xoData' => $xo,
                'relatedData' => [
                    'type' => XoType::select('id', 'name')->find($xo->typeId),
                    'fromCountry' => Country::select('id', 'name')->find($xo->fromCountryId),
                    'shipToCountry' => Country::select('id', 'name')->find($xo->shipToCountryId),
                    'fromState' => State::select('id', 'name')->find($xo->fromStateId),
                    'shipToState' => State::select('id', 'name')->find($xo->shipToStateId),
                    'locationTag' => Location::select('id', 'name')->find($xo->mainLocationTagId),
                    'status' => XoStatus::select('id', 'name')->find($xo->statusId),
                ],
            ];
        });
    
        return response()->json([
            'data' => $data,
            'total' => $picks->total(),
            'perPage' => $picks->perPage(),
            'currentPage' => $picks->currentPage(),
            'lastPage' => $picks->lastPage(),
        ]);
    }
    
    
    

    public function showXoItem(Request $request, $xoId = null, $partNum = null): JsonResponse
        {
            $perPage = $request->input('perPage', 100);
            $page = $request->input('page', 1);
            $type = $request->input('type'); 

            $query = XoItem::query();

            if ($xoId) {
                $query->where('xoId', $xoId);
            }

            if ($partNum) {
                $query->where('partNum', $partNum);
            }

            if ($type) {
                $typeId = XoItemType::where('name', $type)->value('id');
                if ($typeId) {
                    $query->where('typeId', $typeId);
                }
            }

            $picks = $query->paginate($perPage, ['*'], 'page', $page);

            $formattedData = collect($salesOrders->items())->map(function ($item) {
                return [
                    'xoItemData' => $item,
                    'relatedData' => [
                        'type' => XoItemType::select('id', 'name')->find($item->typeId),
                        'status' => XoItemStatus::select('id', 'name')->find($item->statusId),
                        'uom' => UnitOfMeasure::select('id', 'name')->find($item->uomId),
                    ],
                ];
            });

            return response()->json([
                'data' => $data,
                'total' => $picks->total(),
                'perPage' => $picks->perPage(),
                'currentPage' => $picks->currentPage(),
                'lastPage' => $picks->lastPage(),
            ]);
        }



    public function markAsFulfilled(Request $request): JsonResponse
    {
        $data = $request->validate([
            'xoId' => 'required|integer',
        ]);

        try {
            $xo = Xo::findOrFail($data['xoId']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Transfer Order not found',
                'message' => "The Transfer Order with ID '{$data['xoId']}' was not found.",
            ], 404);
        }

        try {
            $status = XoItemStatus::where('name', 'Fulfilled')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Status not found',
                'message' => "The status 'Fulfilled' was not found.",
            ], 404);
        }

        $xo->update(['statusId' => $status->id]);

        foreach ($xo->xoItems as $item) {
            $item->update([
                'qtyFulfilled' => $item->qtyToFulfill,
                'dateLastFulfillment' => Carbon::now(),
                'statusId' => $status->id,
            ]);
        }

        return response()->json([
            'message' => 'Transfer Order marked as Fulfilled.',
            'xo' => $xo,
        ], 200);
    }

    /**
     * Update the status of a Transfer Order to Issued.
     */
    public function markAsIssued(Request $request): JsonResponse
    {
        $data = $request->validate([
            'xoId' => 'required|integer',
        ]);

        try {
            $xo = Xo::findOrFail($data['xoId']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Transfer Order not found',
                'message' => "The Transfer Order with ID '{$data['xoId']}' was not found.",
            ], 404);
        }

        try {
            $status = XoItemStatus::where('name', 'Issued')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Status not found',
                'message' => "The status 'Issued' was not found.",
            ], 404);
        }

        $xo->update([
            'statusId' => $status->id,
            'dateIssued' => Carbon::now(),
        ]);

        foreach ($xo->xoItems as $item) {
            $item->update(['statusId' => $status->id]);
        }

        return response()->json([
            'message' => 'Transfer Order marked as Issued.',
            'xo' => $xo,
        ], 200);
    }
    public function updateStatus(Request $request, string $statusName): JsonResponse
    {
        $data = $request->validate([
            'xoId' => 'required|integer',
        ]);
    
        try {
            $xo = Xo::findOrFail($data['xoId']);
            
            $status = XoStatus::where('name', $statusName)->firstOrFail();
    
            $xo->update(['statusId' => $status->id]);
    
            if ($statusName === 'Fulfilled') {
                $xoItems = XoItem::where('xoId', $xo->id)->get();
                foreach ($xoItems as $xoItem) {
                    $xoItem->update([
                        'statusId' => $status->id,
                        'qtyFulfilled' => $xoItem->qtyToFulfill,
                        'dateLastFulfillment' => Carbon::now(),
                    ]);
                }
    
                return response()->json([
                    'message' => 'Xo and Xo items updated to Fulfilled status successfully.',
                    'xo' => $xo,
                    'xoItems' => $xoItems,
                ], 200);
            }
    
            return response()->json([
                'message' => "Xo status updated to {$statusName} successfully.",
                'xo' => $xo,
            ], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found',
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    

        public function deleteXo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'xoId' => 'required|integer', 
        ]);

        try {
            $xo = Xo::findOrFail($data['xoId']);
            $xo->delete();  
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Transfer Order not found',
                'message' => "The Transfer Order with ID '{$data['xoId']}' was not found.",
            ], 404);
        }

        return response()->json([
            'message' => 'Transfer Order successfully deleted.',
        ], 200);
    }

    public function deleteXoItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'partNum' => 'required|string',  
            'xoId' => 'required|integer', 
        ]);

        try {
            $xoItem = XoItem::where('partNum', $data['partNum'])
                ->where('xoId', $data['xoId']) 
                ->firstOrFail();

            $xoItem->delete(); 
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'XoItem not found',
                'message' => "The XoItem with part number '{$data['partNum']}' and xoId '{$data['xoId']}' was not found.",
            ], 404);
        }

        return response()->json([
            'message' => 'XoItem successfully deleted.',
        ], 200);
    }
}




