<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';
    protected $fillable = ['activeFlag', 'code', 'dateCreated', 'lastChangedUserId', 'name', 'rate', 'symbol'];

    public function getCurrency($name, $code, $symbol, $rate = 1.0)
    {
        // Attempt to find the currency by code
        $currency = $this->where('code', $code)->first();

        // If the currency exists, return its id and rate
        if ($currency) {
            return ['currencyId' => $currency->id, 'rate' => $currency->rate];
        }

        // If the currency does not exist, create a new one and return its id and rate
        $newCurrency = $this->create([
            'activeFlag' => 1,
            'code' => $code,
            'dateCreated' => now(),
            'lastChangedUserId' => '1',
            'name' => $name,
            'rate' => $rate,
            'symbol' => $symbol
        ]);

        return ['currencyId' => $newCurrency->id, 'rate' => $newCurrency->rate];
    }

    public $timestamps = false;
}
