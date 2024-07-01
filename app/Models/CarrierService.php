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
        // Retrieve or create the carrier ID
        $carrier = new Carrier();
        $carrierId = $carrier->getCarrierId($carrierName);

        // Attempt to find the carrier service by carrierId, code, and name
        $query = $this->where('carrierId', $carrierId)
            ->where('code', $code)
            ->where('name', $name);

        $carrierService = $query->first();

        // If the carrier service exists, return its id
        if ($carrierService) {
            return $carrierService->id;
        }

        // If the carrier service does not exist, create a new one and return its id
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
