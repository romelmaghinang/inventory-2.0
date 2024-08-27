<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pick\StorePickRequest;
use App\Models\Pick;
use App\Models\PickItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PickController extends Controller
{
    public function store(StorePickRequest $storePickRequest): JsonResponse
    {
        $pick = Pick::create(
            $storePickRequest->only([
                'dateCreated',
                'dateFinished',
                'dateLastModified',
                'dateScheduled',
                'dateStarted',
                'locationGroupId',
                'num',
                'priority',
                'userId',
            ]) +
                [
                    'statusId' =>  $storePickRequest->pickStatusId,
                    'typeId' => $storePickRequest->pickTypeId,
                ]
        );

        $pickItem = PickItem::create(
            $storePickRequest->only([
                'destTagId',
                'orderId',
                'orderTypeId',
                'partId',
                'poItemId',
                'qty',
                'shipId',
                'slotNum',
                'soItemId',
                'srcLocationId',
                'srcTagId',
                'tagId',
                'uomId',
                'woItemId',
                'xoItemId'
            ]) +
                [
                    'statusId' => $storePickRequest->pickItemStatusId,
                    'typeId' => $storePickRequest->pickItemTypeId,
                    'pickId' => $pick->id,
                ]
        );

        return response()->json(
            [
                'message' => 'Pick Created Successfully!',
                'pickData' => $pick,
                'pickItemData' => $pickItem,
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Pick $pick): JsonResponse
    {
        return response()->json($pick, Response::HTTP_OK);
    }


    public function destroy(Pick $pick): JsonResponse
    {
        $pick->delete();

        return response()->json(
            [
                'message' => 'Pick Deleted Successfully!'
            ],
            Response::HTTP_OK
        );
    }
}
