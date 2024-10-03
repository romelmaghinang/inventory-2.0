<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $table = 'poitem';
    public $timestamps = false;

    protected $fillable = [
        'note',
        'typeId',
        'uomId',
        'productId',
        'productNum',
        'showItemFlag',
        'taxRateCode',
        'taxableFlag',
        'vendorPartNum',
        'description',
        'qtyOrdered',
        'unitPrice',
        'fulfilledQuantity',
        'pickedQuantity',
        'dateScheduledFulfillment',
        'revLevel',
        'customerJob',
        'customFieldItem',
        'poId',
        'qbClassId',
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'poId');
    }

 
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }


    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uomId');
    }


    public function qbClass()
    {
        return $this->belongsTo(qbClass::class, 'qbClassId');
    }
}
