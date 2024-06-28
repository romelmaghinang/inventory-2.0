<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxrate';

    protected $fillable = ['name', 'rate'];

    public function getTaxRateIdByName($name)
    {
        return $this->where('name', $name)->value('id');
    }
}
