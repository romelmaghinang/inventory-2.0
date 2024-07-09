<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'customerName',
        'customerContact',
        'billToAddress',
        'billToCity',
        'billToName',
        'billToZip',
        'dateFirstShip',
        'shipToAddress',
        'shipToCity',
        'shipToName',
        'shipToZip',
        'taxId',
        'countryId',
        'stateId',
        'accountId'
    ];
}
