<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickItem extends Model
{
    use HasFactory;

    protected $table = 'pickitem';

    protected $fillable = [
        'qty',
        'slotNum',
        'srcLocationId',
        'srcTagId',
        'tagId',
        'destTagId',
        'orderId',
        'shipId',
        'orderTypeId',
        'partId',
        'pickId',
        'poItemId',
        'soItemId',
        'statusId',
        'typeId',
        'uomId',
        'woItemId',
        'xoItemId',
    ];

    public $timestamps = false;
}
