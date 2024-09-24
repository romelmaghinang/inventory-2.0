<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNum extends Model
{
    use HasFactory;

    protected $table = 'serialnum';
    public $timestamps = false;
    protected $fillable = [
        'serialId',
        'serialNum',
        'partTrackingId',
    ];

    public function serial()
    {
        return $this->belongsTo(Serial::class, 'serialId');
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tagId');
    }
}
