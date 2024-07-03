<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carrier';
    protected $fillable = ['name', 'description'];

    public function getCarrierId($carrierName)
    {
        $carrier = $this->where('name', $carrierName)->first();
        return $carrier->id;
    }

    public function createCarrier($carrierName, $description)
    {
        $carrier = $this->create(['name' => $carrierName, 'description' => $description]);
        return $carrier->id;
    }

    public $timestamps = false;
}
