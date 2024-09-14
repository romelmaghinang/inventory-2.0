<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartToTracking extends Model
{
    use HasFactory;

    protected $table = 'parttotracking';

    protected $fillable =
    [
        'nextValue',
        'primaryFlag',
        'partTrackingId',
        'partId',
    ];

    public $timestamps = false;

    public function partTracking(): BelongsTo
    {
        return $this->belongsTo(PartTracking::class, 'partTrackingId');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'partId');
    }
}
