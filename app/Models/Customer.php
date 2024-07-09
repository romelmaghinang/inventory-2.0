<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = [
        'activeFlag',
        'defaultPaymentTermsId',
        'name',
        'number',
        'taxExempt',
        'toBeEmailed',
        'toBePrinted',
        'url',
        'customer'
    ];
}

