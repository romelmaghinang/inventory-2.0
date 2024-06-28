<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'ship';

    protected $fillable = ['shipTermsId', 'shipToCountryId', 'shipToStateId'];

    public function getShipTermsIdByName($term)
    {
        return $this->where('shipTermsId', $term)->value('id');
    }

    public function getShipToCountryIdByName($countryName)
    {
        return $this->where('shipToCountryId', $countryName)->value('id');
    }

    public function getShipToStateIdByName($stateName)
    {
        return $this->where('shipToStateId', $stateName)->value('id');
    }
}
