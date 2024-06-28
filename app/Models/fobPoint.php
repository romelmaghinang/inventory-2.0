<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fobPoint extends Model
{
    use HasFactory;

    protected $table = 'fobpoint';
    protected $fillable = ['name'];

    public function getFobPointIdByName($fobPointId)
    {
        return $this->where('name', $fobPointId)->value('id');
    }
}
