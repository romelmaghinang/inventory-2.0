<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingInfo extends Model
{
    use HasFactory;
    
    protected $table = 'trackinginfo';

    protected $fillable = [
        'info',
        'infoDate',
        'infoDouble',
        'infoInteger',
        'partTrackingId',
        'qty',
        'recordId',
        'tableId',
    ];

    public $timestamps = false;
}
