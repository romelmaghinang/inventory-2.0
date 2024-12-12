<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptType extends Model
{
    protected $table = 'receipttype'; 

    protected $fillable = [
        'id',
        'name',
    ];

    public $timestamps = false; 
}
