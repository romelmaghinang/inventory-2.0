<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartType extends Model
{
    protected $table = 'parttype';

    use HasFactory;

    public $timestamps = false;
}