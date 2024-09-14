<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipCarton extends Model
{
    use HasFactory;

    protected $table = 'shipcarton';

    protected $fillable = [
        'additionalHandling',
        'carrierId',
        'cartonNum',
        'cartonTypeId',
        'dateCreated' => 'datetime:Y-m-d',
        'deliveryConfirmationId',
        'freightAmount',
        'freightWeight',
        'height',
        'insuredValue',
        'len',
        'orderId',
        'orderTypeId',
        'packageTypeId',
        'shipId',
        'shipperRelease',
        'sizeUOM',
        'sscc',
        'trackingNum',
        'weightUOM',
        'width',
    ];

    public $timestamps = false;
}
