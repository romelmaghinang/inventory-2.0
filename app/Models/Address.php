<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $fillable = [
        'accountId',
        'name',
        'city',
        'countryId',
        'defaultFlag',
        'locationGroupId',
        'addressName',
        'pipelineContactNum',
        'stateId',
        'address',
        'typeID',
        'zip',
    ];

    public $timestamps = false;
}
