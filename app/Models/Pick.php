<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    use HasFactory;

    protected $table = 'pick';

    protected $fillable = [
        'dateCreated',
        'dateFinished',
        'dateLastModified',
        'dateScheduled',
        'dateStarted',
        'num',
        'userId',
        'locationGroupId',
        'statusId',
        'typeId',
        'priority',
    ];

    public $timestamps = false;
}