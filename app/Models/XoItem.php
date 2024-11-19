<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XoItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xoitem';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dateLastFulfillment',
        'dateScheduledFulfillment',
        'description',
        'lineItem',
        'note',
        'partId',
        'partNum',
        'qtyFulfilled',
        'qtyPicked',
        'qtyToFulfill',
        'revisionNum',
        'statusId',
        'totalCost',
        'typeId',
        'uomId',
        'xoId',
    ];

    /**
     * Relationships
     */

    public function xo()
    {
        return $this->belongsTo(Xo::class, 'xoId', 'id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'partId', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uomId', 'id');
    }

    public function status()
    {
        return $this->belongsTo(XoItemStatus::class, 'statusId', 'id');
    }
}
