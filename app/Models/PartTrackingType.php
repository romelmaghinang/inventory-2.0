<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartTrackingType extends Model
{
    use HasFactory;

    protected $table = 'parttrackingtype';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
