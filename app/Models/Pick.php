<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    use HasFactory;

    protected $table = 'pick';

    protected $fillable = [
        'dateCreated' => 'datetime:Y-m-d',
        'dateFinished',
        'dateLastModified' => 'datetime:Y-m-d',
        'dateScheduled',
        'dateStarted' => 'datetime:Y-m-d',
        'num',
        'userId',
        'locationGroupId',
        'statusId',
        'typeId',
        'priority',
    ];

    public $timestamps = false;
}