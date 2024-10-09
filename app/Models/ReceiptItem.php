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
        'billVendorFlag',
        'orderTypeId',  
        'statusId',
        'typeId',
        'uomId',
        'partTypeId'

      
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receiptId');
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uomId');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'poItemId');
    }
}
