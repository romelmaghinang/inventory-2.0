<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'so'; 

    protected $fillable = [
        'billToAddress', // nullable
        'billToCity', // nullable
        'billToCountryId', // nullable
        'billToName', // nullable
        'billToStateId', // nullable
        'billToZip', // nullable
        'carrierId', // nullable
        'carrierServiceId', // nullable
        'cost', // nullable
        'currencyId', // nullable
        'currencyRate', // nullable
        'customerContact', // nullable
        'customerId', // nullable
        'customerPO', // nullable
        'dateCompleted', // nullable
        'dateCreated', // nullable
        'dateExpired', // nullable
        'dateFirstShip', // nullable
        'dateIssued', // nullable
        'dateLastModified', // nullable
        'dateRevision', // nullable
        'email', // nullable
        'estimatedTax', // nullable
        'locationGroupId', // nullable
        'mcTotalTax', // nullable
        'note', // nullable
        'num', // nullable
        'paymentTermsId', // nullable
        'phone', // nullable
        'priorityId', // nullable
        'qbClassId', // nullable
        'residentialFlag', // default(false)
        'revisionNum', // nullable
        'salesman', // nullable
        'salesmanId', // nullable
        'salesmanInitials', // nullable
        'shipTermsId', // nullable
        'shipToAddress', // nullable
        'shipToCity', // nullable
        'shipToCountryId', // nullable
        'shipToName', // nullable
        'shipToStateId', // nullable
        'shipToZip', // nullable
        'statusId', // nullable
        'taxRate', // nullable
        'taxRateId', // nullable
        'taxRateName', // nullable
        'toBeEmailed', // default(false)
        'toBePrinted', // default(false)
        'totalIncludesTax', // default(false)
        'totalTax', // nullable
        'subTotal', // nullable
        'totalPrice', // nullable
        'typeId', // nullable
        'url', // nullable
        'username', // nullable
        'vendorPO', // nullable
    ];
}
