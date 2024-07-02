<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carrier';
    protected $fillable = ['name', 'description'];

    public function getCarrierId($name, $activeFlag = 1, $description = null)
    {
        // Attempt to find the carrier by name (and optionally description)
        $query = $this->where('name', $name);

        if ($description) {
            $query->where('description', $description);
        }

        $carrier = $query->first();

        // If the carrier exists, return its id
        if ($carrier) {
            return $carrier->id;
        }

        // If the carrier does not exist, create a new one and return its id
        $newCarrier = $this->create([
            'name' => $name,
            'active' => $activeFlag,
            'description' => $description
        ]);

        return $newCarrier->id;
    }

    public $timestamps = false;
}
