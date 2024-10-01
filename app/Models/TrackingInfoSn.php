<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingInfoSn extends Model
{
    use HasFactory;

    protected $table = 'trackinginfosn';
    public $timestamps = false;

    protected $fillable = [
        'partTrackingId',
        'serialNum',
        'trackingInfoId',
    ];

    public function partTracking()
    {
        return $this->belongsTo(PartTracking::class, 'partTrackingId');
    }

    public function trackingInfo()
    {
        return $this->belongsTo(TrackingInfo::class, 'trackingInfoId');
    }
}
