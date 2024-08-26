<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesOrder extends Model
{
    use HasFactory;

    public mixed $accountId;
    protected $table = 'so'; 

    protected $fillable = [
        'billToAddress', 
        'billToCity', 
        'billToCountryId', 
        'billToName', 
        'billToStateId', 
        'billToZip', 
        'carrierId', 
        'carrierServiceId', 
        'cost', 
        'currencyId', 
        'currencyRate', 
        'customerContact', 
        'customerId', 
        'customerPO', 
        'dateCompleted', 
        'dateCreated', 
        'dateExpired', 
        'dateFirstShip', 
        'dateIssued', 
        'dateLastModified', 
        'dateRevision', 
        'email', 
        'estimatedTax', 
        'locationGroupId', 
        'mcTotalTax', 
        'note', 
        'num', 
        'paymentTermsId', 
        'phone', 
        'priorityId', 
        'qbClassId', 
        'residentialFlag', // default(false)
        'revisionNum', 
        'salesman', 
        'salesmanId', 
        'salesmanInitials', 
        'shipTermsId', 
        'shipToAddress', 
        'shipToCity', 
        'shipToCountryId', 
        'shipToName', 
        'shipToStateId', 
        'shipToZip', 
        'statusId', 
        'taxRate', 
        'taxRateId', 
        'taxRateName', 
        'toBeEmailed', // default(false)
        'toBePrinted', // default(false)
        'totalIncludesTax', // default(false)
        'totalTax', 
        'subTotal', 
        'totalPrice', 
        'typeId', 
        'url', 
        'username', 
        'vendorPO', 
    ];

    public $timestamps = false;
}
