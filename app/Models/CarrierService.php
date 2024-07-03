<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierService extends Model
{
    use HasFactory;

    protected $table = 'carrierService';
    protected $fillable = ['activeFlag', 'carrierId', 'code', 'name', 'readOnly'];

    public function getCarrierServiceId($carrierName, $code, $name)
    {
       $carrierService = $this->where('name', $carrierName)->where('code', $code)->first();

       return $carrierService->id;
    }

    public function createCarrierService($carrierId, $code, $name, $readOnly)
    {
        $newCarrierService = $this->create([
            'activeFlag' => 1,
            'carrierId' => $carrierId,
            'code' => $code,
            'name' => $name,
            'readOnly' => 0,
        ]);

        return $newCarrierService->id;
    }

    public $timestamps = false;
}
