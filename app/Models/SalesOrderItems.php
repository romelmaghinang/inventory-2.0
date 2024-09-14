<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesOrderItems extends Model
{
    use HasFactory;

    protected $table = 'soitem';

    protected $fillable = [
        'adjustAmount', 
        'adjustPercentage',  
        'customerPartNum',  
        'dateLastFulfillment',  
        'dateLastModified',  
        'dateScheduledFulfillment',  
        'description',  
        'exchangeSOLineItem',  
        'itemAdjustId',  
        'markupCost',  
        'mcTotalPrice',  
        'note',
        'productId',  
        'productNum',  
        'qbClassId',  
        'qtyFulfilled',  
        'qtyOrdered',  
        'qtyPicked',  
        'qtyToFulfill',  
        'revLevel',  
        'showItemFlag',
        'soId',
        'soLineItem',
        'statusId',
        'taxId',  
        'taxRate',  
        'taxableFlag',
        'totalCost',  
        'totalPrice',  
        'typeId',
        'unitPrice',  
        'uomId',  
    ];

    public $timestamps = false;

    public function salesOrder(): HasOne
    {
        return $this->hasOne(SalesOrder::class, 'id', 'soId');
    }
}
