<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    use HasFactory;

    protected $table = 'paymentterms';

    protected $fillable = ['name'];

    public function getPaymentTermsId($paymentTermsName)
    {
        $paymentTerms = $this->where('name', $paymentTermsName)->first();

        return $paymentTerms->id;
    }

    public $timestamps = false;
}
