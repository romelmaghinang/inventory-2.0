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
        'statusId',
        'taxExempt',
        'toBeEmailed',
        'toBePrinted',
        'url'
    ];

    public function getOrCreateCustomer($name, $defaultPaymentTermsId, $statusId, $number, $taxExempt, $toBeEmailed, $toBePrinted, $url)
    {
        // Attempt to find the customer by name
        $customer = $this->where('name', $name)->first();

        // If the customer exists, return its details
        if ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name
            ];
        }

        // If the customer does not exist, create a new one and return its details
        $newCustomer = $this->create([
            'activeFlag' => 1,
            'defaultPaymentTermsId' => $defaultPaymentTermsId,
            'name' => $name,
            'number' => $number,
            'statusId' => $statusId,
            'taxExempt' => $taxExempt,
            'toBeEmailed' => $toBeEmailed,
            'toBePrinted' => $toBePrinted,
            'url' => $url
        ]);

        return [
            'id' => $newCustomer->id,
            'name' => $newCustomer->name
        ];
    }

    public $timestamps = false;

}

