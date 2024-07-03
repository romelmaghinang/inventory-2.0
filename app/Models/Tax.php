<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxrate';


    protected $fillable = ['taxRateId', 'taxRateName'];

    public function getTaxRateData($taxRateName)
    {
        // Attempt to find the tax rate by name
        $taxRate = $this->where('taxRateName', $taxRateName)->first();

            return [
                'taxRateId' => $taxRate->taxRateId,
                'taxRateName' => $taxRate->taxRateName
            ];
    }

    public function createTaxRate($taxRateName)
    {
        // If the tax rate does not exist, create a new one and return its details
        return $this->create([
            'taxRateName' => $taxRateName
        ]);
    }

    public $timestamps = false;
}
