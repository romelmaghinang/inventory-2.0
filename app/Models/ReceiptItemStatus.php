<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItemsStatus extends Model
{
    use HasFactory;

    protected $table = 'receiptitemsstatus'; 
    public $timestamps = false; 

    protected $fillable = [
        'id', 
        'name',
    ];

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class, 'statusId');
    }
}
