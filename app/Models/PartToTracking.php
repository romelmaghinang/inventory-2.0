<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartToTracking extends Model
{
    use HasFactory;

    protected $table = 'parttotracking';

    protected $fillable =
    [
        'nextValue',
        'primaryFlag',
        'partTrackingId',
        'partId',
    ];

    public $timestamps = false;
}
