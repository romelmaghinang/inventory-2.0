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
        $salesman = $this->where('SalesmanId', $salesmanId)->first();

            return [
                'salesmanId' => $salesman,
                'salesman' => $salesmanName,
                'salesmanInitials' => $salesmanInitials
            ];
    }

    public function createSalesman($salesman, $salesmanInitials)
    {
        return $this->create([
            'salesman' => $salesman,
            'salesmanInitials' => $salesmanInitials
        ]);
    }
        public $timestamps = false;

}
