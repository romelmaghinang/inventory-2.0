<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItemType extends Model
{
    use HasFactory;

    protected $table = 'poitemtype';

    public $timestamps = false;
}
