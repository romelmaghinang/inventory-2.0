<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'paymentterms';
    protected $fillable = ['activeFlag', 'defaultTerm', 'name', 'readOnly', 'typeId'];

    public function getPaymentTerm($name, $defaultTerm = 1, $readOnly = 0, $typeName)
    {
        // Retrieve the typeId from the paymenttermstype table
        $typeId = PaymentTermsType::where('name', $typeName)->value('id');

        if (!$typeId) {
            // Create a new type if it doesn't exist
            $type = PaymentTermsType::create(['name' => $typeName]);
            $typeId = $type->id;
        }

        // Attempt to find the payment term by name
        $paymentTerm = $this->where('name', $name)->first();

        // If the payment term exists, return its id
        if ($paymentTerm) {
            return ['id' => $paymentTerm->id];
        }

        // If the payment term does not exist, create a new one and return its id
        $newPaymentTerm = $this->create([
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
