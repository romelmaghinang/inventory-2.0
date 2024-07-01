<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qbClass extends Model
{
    use HasFactory;

    protected $table = 'qbclass';
    protected $fillable = ['id', 'accountingHash', 'accountingId', 'activeFlag', 'dateCreated', 'name', 'parentId'];
    public function getQbClassIdByName($name)
    {
        // Attempt to find the QB class by name
        $qbClass = $this->where('name', $name)->first();

        // If the QB class exists, return its id
        if ($qbClass) {
            return $qbClass->id;
        }
    }
}
