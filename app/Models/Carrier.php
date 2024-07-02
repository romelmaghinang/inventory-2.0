<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'carrier';
    protected $fillable = ['name', 'description'];

    public function getCarrierId($name, $description = null)
    {
        // Attempt to find the carrier by name (and optionally description)
        $query = $this->where('name', $name);

        if ($description) {
            $query->where('description', $description);
        }

        $carrier = $query->first();
        return $carrier->id;
    }

    public function createCarrier($name, $description)
    {
        $carrier = $this->createCarrier(['name' => $name, 'description' => $description]);
        return $carrier->id;
    }

    public $timestamps = false;
}
