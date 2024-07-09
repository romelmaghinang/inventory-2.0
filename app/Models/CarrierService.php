<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarrierService extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        // 'active_flag',
        'code',
        'name',
        // 'read_only'
    ];

    public function carrier(): HasOne
    {
        return $this->hasOne(Carrier::class);
    }
}