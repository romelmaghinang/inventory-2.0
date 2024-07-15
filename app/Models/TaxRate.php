<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $table = 'taxrate';

    protected $fillable = [
        'accountingHash', // nullable
        'accountingId', // nullable
        'activeFlag',
        'code', // nullable
        'dateCreated', // nullable
        'dateLastModified', // nullable
        'defaultFlag',
        'description', // nullable
        'name', // nullable
        'orderTypeId', // nullable
        'rate', // nullable
        'taxAccountId', // nullable
        'typeCode', // nullable
        'typeId', // nullable
        'unitCost', // nullable
        'vendorId', // nullable
    ];
}
