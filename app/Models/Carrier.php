<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carrier';
    protected $fillable = ['carrierId', 'carrierServiceId'];

    public function getCarrierIdByName($carrierId)
    {
        return $this->where('name', $carrierId)->value('id');
    }

    public function getCarrierServiceIdByName($carrierServiceId)
    {
        return $this->where('name', $carrierServiceId)->value('id');
    }
}
