<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'paymentterms';
    protected $fillable = ['name'];

    public function getPaymentTermsIdByName(mixed $paymentTermsId)
    {
        return $this->where('name', $paymentTermsId)->value('id');
    }
}
