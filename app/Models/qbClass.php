<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qbClass extends Model
{
    use HasFactory;

    protected $table = 'qbclass';
    protected $fillable = ['name'];
    public function getQbClassIdByName($qbClassId)
    {
        return $this->where('name', $qbClassId)->value('id');
    }
}
