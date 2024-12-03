<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postatus extends Model
{
    use HasFactory;

    protected $table = 'postatus';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'statusId');
    }
}
