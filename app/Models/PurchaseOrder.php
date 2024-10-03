<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'po';
    public $timestamps = false;

    protected $fillable = [
        'vendorContact',
        'remitToName',
        'remitToAddress',
        'remitToCity',
        'remitToZip',
        'shipToName',
        'deliverToName',
        'shipToAddress',
        'shipToCity',
        'shipToZip',
        'vendorSONum',
        'customerSONum',
        'createdDate',
        'completedDate',
        'confirmedDate',
        'fulfillmentDate',
        'issuedDate',
        'buyer',
        'paymentTerms',
        'fob',
        'note',
        'phone',
        'email',
        'url',
        'customField',
        'currencyRate',
        'locationGroupId',
        'activeFlag',
        'shipTermsId',
        'remitToCountryId',
        'remitToStateId',
        'shipToCountryId',
        'shipToStateId',
        'statusId',
        'currencyId',
        'vendorId',
        'carrierId',
        'carrierServiceId',
        'qbClassId',
        'num',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendorId');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'poId');
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencyId');
    }

 
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrierId');
    }


    public function carrierService()
    {
        return $this->belongsTo(CarrierService::class, 'carrierServiceId');
    }

  
    public function qbClass()
    {
        return $this->belongsTo(qbClass::class, 'qbClassId');
    }


    public function locationGroup()
    {
        return $this->belongsTo(LocationGroup::class, 'locationGroupId');
    }


    public function shipTerms()
    {
        return $this->belongsTo(ShipTerms::class, 'shipTermsId');
    }


    public function remitToCountry()
    {
        return $this->belongsTo(Country::class, 'remitToCountryId');
    }


    public function remitToState()
    {
        return $this->belongsTo(State::class, 'remitToStateId');
    }


    public function shipToCountry()
    {
        return $this->belongsTo(Country::class, 'shipToCountryId');
    }


    public function shipToState()
    {
        return $this->belongsTo(State::class, 'shipToStateId');
    }
}
