<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPart extends Model
{
    use HasFactory;

    protected $table = 'vendorparts'; 
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'dateCreated',
        'dateLastModified',
        'defaultFlag',
        'lastCost',
        'lastDate',
        'leadTime',
        'partId',
        'qtyMax',
        'qtyMin',
        'uomId',
        'userId',
        'vendorId',
        'vendorPartNumber',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'partId');
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uomId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendorId');
    }
}
