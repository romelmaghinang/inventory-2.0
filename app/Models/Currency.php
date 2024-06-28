<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';
    protected $fillable = ['name', 'rate'];

    public function getCurrencyIdByName($name)
    {
        return $this->where('name', $name)->value('id');
    }

    public function getCurrencyRateByName($name)
    {
        return $this->where('rate', $name)->value('rate');
    }
}
