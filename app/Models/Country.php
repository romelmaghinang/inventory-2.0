<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'country';
    protected $fillable = ['name'];

    public function getCountryIdByName($countryName)
    {
        $country = $this->where('name', $countryName)->first();
        return $country->id;
    }

    public $timestamps = false;
}
