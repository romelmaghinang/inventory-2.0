<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'so';

    // Specify the fillable fields
    protected $fillable = [
        'status', 'customerName', 'customerContact', 'billToAddress',
        'billToCity', 'billToCountryId', 'billToName', 'billToStateId', 'billToZip',
        'carrierId', 'carrierServiceId', 'Cost', 'currencyId', 'currencyRate', 'customerId',
        'customerPO', 'dateCompleted', 'dateCreate', 'dateExpired', 'dateFirstShip',
        'dateIssued', 'dateLastModified', 'dateRevision', 'email', 'estimatedTax',
        'fobPointId', 'locationGroupId', 'mcTotalTax', 'note', 'num', 'paymentTermsId',
        'phone', 'priorityId', 'qbClassId', 'registerId', 'residentialFlag', 'revisionNum',
        'salesman', 'salesmanId', 'salesmanInitials', 'shipTermsId', 'shipToAddress',
        'shipToCity', 'shipToCountryId', 'shipToName', 'shipToStateId', 'shipToZip',
        'statusId', 'taxRate', 'taxRateId', 'taxRateName', 'toBeEmailed', 'toBePrinted',
        'totalIncludesTax', 'totalTax', 'subTotal', 'totalPrice', 'typeId', 'url',
        'username', 'vendorPO'
    ];

    // Disable timestamps
    public $timestamps = false;

}
