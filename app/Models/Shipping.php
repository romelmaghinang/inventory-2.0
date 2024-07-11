<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'ship';

    protected $fillable = [
        'FOBPointId',
        'billOfLading',
        'carrierId',
        'carrierServiceId',
        'cartonCount',
        'contact',
        'dateCreated',
        'dateLastModified',
        'dateShipped',
        'locationGroupId',
        'note',
        'num',
        'orderTypeId',
        'ownerIsFrom',
        'poId',
        'shipToId',
        'shipmentIdentificationNumber',
        'shippedBy',
        'soId',
        'statusId',
        'xoId'
    ];
}
