<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'receiptitem';
    public $timestamps = false;

    protected $fillable = [
        'receiptId',
        'poItemId',
        'qty',
        'locationId',
        'dateReceived',
        'trackingNum',
        'packageCount',
        'carrierId',
        'carrierServiceId',
        'dateLastModified',
    ];


    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receiptId');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'poItemId');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locationId');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrierId');
    }

    public function carrierService()
    {
        return $this->belongsTo(CarrierService::class, 'carrierServiceId');
    }

}
