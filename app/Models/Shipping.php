<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'ship';

    protected $fillable = ['shipTermsId', 'shipToCountryId', 'shipToStateId'];

    public function getShippingData($shipTermsId, $shipToCountryId, $shipToStateId): array
    {
        // Attempt to find the shipping entry by shipTermsId, shipToCountryId, and shipToStateId
        $shipping = $this->where('shipTermsId', $shipTermsId)
            ->where('shipToCountryId', $shipToCountryId)
            ->where('shipToStateId', $shipToStateId)
            ->first();

        // If the shipping entry exists, return its details
        if ($shipping) {
            return [
                'shipTermsId' => $shipping->shipTermsId,
                'shipToCountryId' => $shipping->shipToCountryId,
                'shipToStateId' => $shipping->shipToStateId
            ];
        }

        // If the shipping entry does not exist, create a new one and return its details
        $newShipping = $this->create([
            'shipTermsId' => $shipTermsId,
            'shipToCountryId' => $shipToCountryId,
            'shipToStateId' => $shipToStateId
        ]);

        return [
            'shipTermsId' => $newShipping->shipTermsId,
            'shipToCountryId' => $newShipping->shipToCountryId,
            'shipToStateId' => $newShipping->shipToStateId
        ];
    }
    public $timestamps = false;

}
