<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    
    protected $fillable = [
        'accountNum',
        'accountingHash',
        'accountingId',
        'activeFlag',
        'creditLimit',
        'currencyRate',
        'dateEntered',
        'dateLastModified',
        'lastChangedUser',
        'leadTime',
        'minOrderAmount',
        'name',
        'note',
        'url',
        'accountId',
        'currencyId',
        'defaultCarrierId',
        'defaultPaymentTermsId',
        'defaultShipTermsId',
        'statusId',
        'sysUserId',
        'taxRateId',
    ];

    public $timestamps = false;
}
