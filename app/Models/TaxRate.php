<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $table = 'taxrate';

    protected $fillable = [
        'accountingHash',
        'accountingId',
        'activeFlag',
        'code',
        'dateCreated',
        'dateLastModified',
        'defaultFlag',
        'description',
        'name',
        'orderTypeId',
        'rate',
        'taxAccountId',
        'typeCode',
        'typeId',
        'unitCost',
        'vendorId',
    ];
}
