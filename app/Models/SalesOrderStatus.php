<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatus extends Model
{
    use HasFactory;

    protected $table = 'sostatus';

    protected $fillable =
    [
        'name'
    ];
    
}
