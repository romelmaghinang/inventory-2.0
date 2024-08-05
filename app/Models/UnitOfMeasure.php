<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $table = 'uom';

    protected $fillable = [
        'activeFlag',
        'code',
        'defaultRecord',
        'description',
        'integral',
        'name',
        'readOnly',
        'uomType',
    ];

    public $timestamps = false;
}
