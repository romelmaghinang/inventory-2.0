<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipItem extends Model
{
    use HasFactory;

    protected $table = 'shipitem';

    protected $fillable = [
        'dateLastModified' => 'datetime:Y-m-d',
        'itemId',
        'orderId',
        'orderTypeId',
        'poItemId',
        'qtyShipped',
        'shipCartonId',
        'shipId',
        'soItemId',
        'tagId',
        'totalCost',
        'uomId',
        'xoItemId',
    ];


    public $timestamps = false;
}
