<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationGroup extends Model
{
    use HasFactory;

    protected $table = 'locationgroup';

    protected $fillable = [
        'activeFlag',
        'dateLastModified',
        'name',
        'qbClassId',
    ];

    public $timestamps = false;
}
