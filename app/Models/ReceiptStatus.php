<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptStatus extends Model
{
    use HasFactory;

    protected $table = 'receiptstatus'; 
    public $timestamps = false;

    protected $fillable = [
        'id', 
        'name',
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'statusId');
    }
}
