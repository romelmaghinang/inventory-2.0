<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;
    protected $table = 'so';
    protected $fillable = ['salesmanId','salesman', 'salesmanInitials'];

    public function getSalesmanData($salesmanId, $salesmanName, $salesmanInitials)
    {
        // Attempt to find the salesman details by SalesmanId
        $salesman = $this->where('SalesmanId', $salesmanId)->first();

        // If the salesman exists, return its details
        if ($salesman) {
            return [
                'SalesmanId' => $salesman->SalesmanId,
                'salesman' => $salesman->salesman,
                'salesmanInitials' => $salesman->salesmanInitials
            ];
        }

        // If the salesman does not exist, create a new one and return its details
        $newSalesman = $this->create([
            'SalesmanId' => $salesmanId,
            'salesman' => $salesmanName,
            'salesmanInitials' => $salesmanInitials
        ]);

        return [
            'SalesmanId' => $newSalesman->SalesmanId,
            'salesman' => $newSalesman->salesman,
            'salesmanInitials' => $newSalesman->salesmanInitials
        ];
    }

    public $timestamps = false;
}
