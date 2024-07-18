<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';

    protected $fillable = [
        'activeFlag',
        'code',
        'dateCreated',
        'dateLastModified',
        'excludeFromUpdate',
        'homeCurrency',
        'lastChangedUserId',
        'name',
        'rate',
        'symbol',
    ];

    public $timestamps = false;
}
