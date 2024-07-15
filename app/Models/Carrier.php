<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carrier';

    protected $fillable = [
        'activeFlag',
        'description',
        'name',
        'readOnly',
        'scac',
    ];

    public $timestamps = false;
}
