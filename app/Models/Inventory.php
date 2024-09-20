<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventorylog';

    protected $fillable = [
        'begLocationId',
        'begTagNum',
        'changeQty',
        'cost',
        'dateCreated',
        'endLocationId',
        'endTagNum',
        'eventDate',
        'info',
        'locationGroupId',
        'partId',
        'partTrackingId',
        'qtyOnHand',
        'recordId',
        'tableId',
        'typeId',
        'userId',
    ];

    public $timestamps = false;
}
