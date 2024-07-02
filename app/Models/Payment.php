<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'paymentterms';
    protected $fillable = ['activeFlag', 'defaultTerm', 'name', 'readOnly', 'typeId'];

    public function getPaymentTermsId($name, $typeName)
    {
        // Retrieve the typeId from the paymenttermstype table
        $type = PaymentTermsType::where('name', $typeName)->value('id');
        $typeId = $type->id;

        // Attempt to find the payment term by name
        $paymentTerm = $this->where('name', $name)->first();

        return ['id' => $paymentTerm->id, 'typeId' => $typeId];

    }

    public function createPaymentTerm($name, $defaultTerm = 1, $readOnly = 0, $typeId)
    {
        $newPaymentTerm = $this->createPaymentTerm($name, $defaultTerm, $readOnly, $typeId);([
            'activeFlag' => 1,
            'defaultTerm' => $defaultTerm,
            'name' => $name,
            'readOnly' => $readOnly,
            'typeId' => $typeId
        ]);

        return ['id' => $newPaymentTerm->id];
    }
    public $timestamps = false;
}
