<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTermsType extends Model
{
    use HasFactory;

    protected $table = 'paymenttermstype'; // Ensure this matches your table name

    protected $fillable = ['name'];
}
