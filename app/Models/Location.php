<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';

    protected $fillable = [
        'activeFlag',
        'countedAsAvailable',
        'dateLastModified',
        'defaultCustomerId',
        'defaultFlag',
        'defaultVendorId',
        'description',
        'locationGroupId',
        'name',
        'pickable',
        'receivable',
        'sortOrder',
        'typeId',
    ];
}
