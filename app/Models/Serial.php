<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory;

    protected $table = 'serial'; 
    public $timestamps = false;
    protected $fillable = [
        'committedFlag',
        'tagId',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tagId');
    }

    public function serialNums()
    {
        return $this->hasMany(SerialNum::class, 'serialId');
    }
    
}
