<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xo';

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
        'carrierId',
        'dateCompleted',
        'dateConfirmed',
        'dateCreated',
        'dateFirstShip',
        'dateIssued',
        'dateLastModified',
        'dateScheduled',
        'fromAddress',
        'fromAttn',
        'fromCity',
        'fromCountryId',
        'fromLGId',
        'fromName',
        'fromStateId',
        'fromZip',
        'mainLocationTagId',
        'note',
        'num',
        'ownerIsFrom',
        'revisionNum',
        'shipToAddress',
        'shipToAttn',
        'shipToCity',
        'shipToCountryId',
        'shipToLGId',
        'shipToName',
        'shipToStateId',
        'shipToZip',
        'statusId',
        'typeId',
        'userId',
    ];

    /**
     * Define relationships.
     */

    // Relationship with XoType
    public function type()
    {
        return $this->belongsTo(XoType::class, 'typeId', 'id');
    }

    // Relationship with Carrier
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrierId', 'id');
    }

    // Relationship with LocationGroup for "From"
    public function fromLocationGroup()
    {
        return $this->belongsTo(LocationGroup::class, 'fromLGId', 'id');
    }

    // Relationship with LocationGroup for "Ship To"
    public function shipToLocationGroup()
    {
        return $this->belongsTo(LocationGroup::class, 'shipToLGId', 'id');
    }

    // Relationship with Status
    public function status()
    {
        return $this->belongsTo(XoItemStatus::class, 'statusId', 'id');
    }

    // Relationship with XoItem
    public function items()
    {
        return $this->hasMany(XoItem::class, 'xoId', 'id');
    }
}
