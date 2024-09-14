<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $table = 'ship';

    use HasFactory;

    protected $fillable = [
        'FOBPointId',
        'billOfLading',
        'cartonCount',
        'contact',
        'dateShipped',
        'note',
        'num',
        'orderTypeId',
        'ownerIsFrom',
        'poId',
        'shipToId',
        'shipmentIdentificationNumber',
        'shippedBy',
        'statusId',
        'xoId',
        'carrierId',
        'carrierServiceId',
        'locationGroupId',
        'soId',
    ];

    public $timestamps = false;
}
