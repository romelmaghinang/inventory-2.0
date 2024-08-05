<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'part';

    use HasFactory;

    protected $fillable = [
        'abcCode',
        'accountingHash',
        'accountingId',
        'activeFlag',
        'adjustmentAccountId',
        'alertNote',
        'alwaysManufacture',
        'cogsAccountId',
        'configurable',
        'consumptionRate',
        'controlledFlag',
        'cycleCountTol',
        'dateCreated',
        'dateLastModified',
        'defaultBomId',
        'defaultOutsourcedReturnItemId',
        'defaultPoItemTypeId',
        'defaultProductId',
        'description',
        'details',
        'height',
        'inventoryAccountId',
        'lastChangedUser',
        'leadTime',
        'len',
        'num',
        'partClassId',
        'pickInUomOfPart',
        'receivingTol',
        'revision',
        'scrapAccountId',
        'serializedFlag',
        'sizeUomId',
        'stdCost',
        'taxId',
        'trackingFlag',
        'typeId',
        'uomId',
        'upc',
        'url',
        'varianceAccountId',
        'weight',
        'weightUomId',
        'width',
        'customFields',
    ];

    public $timestamps = false;
}
