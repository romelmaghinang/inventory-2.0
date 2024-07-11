<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItemType extends Model
{
    use HasFactory;

    protected $table = 'soitemtype';

    protected $fillable =
    [
        'name',
    ];

    public $timestamps = false;
}
