<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Xo;
use App\Models\XoItem;
use App\Models\Part;
use App\Models\UnitOfMeasure;
use App\Models\Address;
use App\Models\Country;
use App\Models\LocationGroup;
use App\Models\XoItemStatus;
use App\Models\Carrier;
use App\Models\XoType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferOrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'TO' => 'required|array',
            'TO.TONum' => 'nullable|string',
            'TO.TOType' => 'required|string',
            'TO.Status' => 'required|string',
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
            'TO.IssuedDate' => 'required|date',
            'TO.CreatedDate' => 'required|date',
            'TO.ConfirmedDate' => 'nullable|date',
            'TO.FulfillmentDate' => 'nullable|date',
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

        if (empty($data['TO']['TONum'])) {
            $data['TO']['TONum'] = 'TO-' . strtoupper(uniqid());
        }

        try {
            $status = XoItemStatus::where('name', $data['TO']['Status'])->firstOrFail();
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

        $fromAddress = Address::firstOrCreate([
            'addressName' => $data['TO']['FromAddressName'],
            'addressStreet' => $data['TO']['FromAddressStreet'],
            'addressCity' => $data['TO']['FromAddressCity'],
            'addressZip' => $data['TO']['FromAddressZip'],
            'country_id' => $country->id,
        ]);

        try {
            $toLocationGroup = LocationGroup::where('name', $data['TO']['ToLocationGroup'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'To Location Group not found',
                'message' => "The location group '{$data['TO']['ToLocationGroup']}' was not found.",
            ], 404);
        }

        $xo = Xo::create([
            'num' => $data['TO']['TONum'],
            'statusId' => $status->id,
            'typeId' => $toType->id,
            'carrierId' => $carrier->id,
            'fromLGId' => $fromLocationGroup->id,
            'shipToLGId' => $toLocationGroup->id,
            'ownerIsFrom' => $data['TO']['OwnerIsFrom'],
            'dateCreated' => Carbon::now(),
            'dateIssued' => Carbon::parse($data['TO']['IssuedDate']),
            'dateConfirmed' => Carbon::parse($data['TO']['ConfirmedDate'] ?? now()),
            'note' => $data['TO']['Note'] ?? null,
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
            ]);

            switch ($data['TO']['Status']) {
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

            if ($data['TO']['Status'] === 'Fulfilled') {
                $xoItem->update(['dateLastFulfillment' => Carbon::now()]);
            }
        }

        return response()->json([
            'message' => 'Transfer Order successfully created.',
            'xo' => $xo,
            'xoItems' => $xo->xoItems,
        ], 201);
    }
}
