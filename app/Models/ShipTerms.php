<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipTerms extends Model
{
    use HasFactory;

    protected $table = 'shipterms';

    protected $fillable =
    [
        'activeFlag',
        'name',
        'readOnly'
    ];
}
