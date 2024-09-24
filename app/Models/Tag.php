<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tag'; 
    protected $fillable = [
        'dateCreated',
        'dateLastCycleCount',
        'dateLastModified',
        'num',
        'qty',
        'qtyCommitted',
        'serializedFlag',
        'trackingEncoding',
        'usedFlag',
        'woItemId',
        'partId',
        'typeId',
        'locationId',
    ];

    public $timestamps = false; 

    public function part()
    {
        return $this->belongsTo(Part::class, 'partId');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locationId');
    }
}
