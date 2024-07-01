<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'ship';

    protected $fillable = ['shipTermsId', 'shipToCountryId', 'shipToStateId', 'statusId'];

    public function getShippingData($shipTermsId, $shipToCountryId, $shipToStateId, $statusId): array
    {
        // Attempt to find the shipping entry by shipTermsId, shipToCountryId, and shipToStateId
        $shipping = $this->where('shipTermsId', $shipTermsId)
            ->where('shipToCountryId', $shipToCountryId)
            ->where('shipToStateId', $shipToStateId)
            ->where('statusId', $statusId)
            ->first();

        // If the shipping entry exists, return its details
        if ($shipping) {
            return [
                'shipTermsId' => $shipping->shipTermsId,
                'shipToCountryId' => $shipping->shipToCountryId,
                'shipToStateId' => $shipping->shipToStateId,
                'statusId' => $shipping->statusId
            ];
        }

        // If the shipping entry does not exist, create a new one and return its details
        $newShipping = $this->create([
            'shipTermsId' => $shipTermsId,
            'shipToCountryId' => $shipToCountryId,
            'shipToStateId' => $shipToStateId,
            'statusId' => $statusId
        ]);

        return [
            'shipTermsId' => $newShipping->shipTermsId,
            'shipToCountryId' => $newShipping->shipToCountryId,
            'shipToStateId' => $newShipping->shipToStateId,
            'statusId' => $newShipping->statusId
        ];
    }
    public $timestamps = false;

}
