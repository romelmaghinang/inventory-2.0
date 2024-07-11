<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRateType extends Model
{
    use HasFactory;

    protected $table = 'taxratetype';

    protected $fillable =
    [
        'name',
    ];
}
