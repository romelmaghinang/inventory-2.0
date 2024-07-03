<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';
    protected $fillable = ['activeFlag', 'code', 'dateCreated', 'lastChangedUserId', 'name', 'rate', 'symbol'];

    public function getCurrency($code)
    {
        // Attempt to find the currency by code
        $currency = $this->where('code', $code)->first();

        return $currency->code;
    }
    public function createCurrency($name, $code, $rate, $symbol)
    {
        $newCurrency = $this->create($name, $code, $symbol, $rate);([
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
