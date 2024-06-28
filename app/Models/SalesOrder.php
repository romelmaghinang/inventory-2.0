<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'so';

    protected $fillable = [
        'billToAddress', 'billToCity', 'billToCountryId', 'billToName', 'billToStateId', 'billToZip', 'carrierId',
        'carrierServiceId', 'cost', 'currencyId', 'currencyRate', 'customerContact', 'customerId', 'customerPO', 'dateCompleted',
        'dateCreated', 'dateExpired', 'dateFirstShip', 'dateIssued', 'dateLastModified', 'dateRevision', 'email', 'estimatedTax',
        'fobPointId', 'locationGroupId', 'mcTotalTax', 'note', 'num', 'paymentTermsId', 'phone', 'priorityId', 'qbClassId',
        'registerId', 'residentialFlag', 'revisionNum', 'salesman', 'salesmanId', 'salesmanInitials', 'shipTermsId', 'shipToAddress',
        'shipToCity', 'shipToCountryId', 'shipToName', 'shipToStateId', 'shipToZip', 'statusId', 'taxRate', 'taxRateId',
        'taxRateName', 'toBeEmailedy', 'toBePrintedy', 'totalIncludesTaxy', 'totalTax', 'subTotal', 'totalPrice', 'typeId', 'url',
        'username', 'vendorPO'
    ];

    // Disable timestamps
    public $timestamps = false;
}
