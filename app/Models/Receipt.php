<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipt';  
    public $timestamps = false;

    protected $fillable = [
        'poId',
        'locationGroupId',
        'orderTypeId',  
        'statusId',
        'typeId',
        'userId'
    ];

    public function items()
    {
        return $this->hasMany(ReceiptItem::class, 'receiptId');
    }

    public function locationGroup()
    {
        return $this->belongsTo(LocationGroup::class, 'locationGroupId');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendorId');
    }
    public function status()
    {
        return $this->belongsTo(ReceiptStatus::class, 'statusId');
    }
}
