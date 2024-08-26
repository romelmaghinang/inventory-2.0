<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartTracking extends Model
{
    use HasFactory;

    protected $table = 'parttracking';

    protected $fillable = [
        'abbr',
        'activeFlag',
        'description',
        'name',
        'sortOrder',
        'typeId',
    ];

    public $timestamps = false;
}