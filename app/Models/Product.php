<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'accountingHash', // nullable
        'accountingId', // nullable
        'activeFlag',
        'alertNote', // nullable
        'dateCreated', // nullable
        'dateLastModified', // nullable
        'defaultSoItemType',
        'description', // nullable
        'details',
        // 'displayTypeId',
        'heigh', // nullable
        // 'incomeAccountId',
        'kitFlag',
        'kitGroupedFlag',
        'len', // nullable
        'num', // nullable
        // 'partId', // nullable
        'price', // nullable
        // 'qbClassId', // nullable
        'sellableInOtherUoms',
        'showSoComboFlag',
        // 'sizeUomId', // nullable
        'sku', // nullable
        // 'taxId', // nullable
        'taxableFlag',
        // 'uomId',
        'upc', // nullable
        'url', // nullable
        'usePriceFlag',
        'weight', // nullable
        // 'weightUomId', // nullable
        'width', // nullable
    ];

    public $timestamps = false;
}
