<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    protected $fillable = [
        'accountId', 
        'accountingHash', // nullable
        'accountingId', // nullable
        'activeFlag', // nullable
        'creditLimit', // nullable
        'currencyId', // nullable
        'currencyRate', // nullable
        'dateCreated', // nullable
        'dateLastModified', // nullable
        'defaultCarrierId', // nullable
        'defaultPaymentTermsId', // nullable
        'defaultSalesmanId', 
        'defaultShipTermsId', // nullable
        'jobDepth', // nullable
        'lastChangedUser', // nullable
        'name', 
        'note', // nullable
        'number', // nullable
        'parentId', // nullable
        'pipelineAccountNum', // nullable
        'qbClassId', // nullable
        'statusId', 
        'sysUserId', // nullable
        'taxExempt', 
        'taxExemptNumber', // nullable
        'taxRateId', // nullable
        'toBeEmailed', 
        'toBePrinted', 
        'url', // nullable
        'issuableStatusId', // nullable
        'carrierServiceId', // nullable
    ];

    public $timestamps = false;
}
