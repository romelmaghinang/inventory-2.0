<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qbClass extends Model
{
    use HasFactory;

    protected $table = 'qbclass';

    protected $fillable = [
        'accountingHash',
        'accountingId',
        'activeFlag',
        'dateCreated',
        'dateLastModified',
        'name'
    ];
    
}
