<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    /*
    protected $fillable = [
        'addressName', 'name', 'city', 'zip', 'residentialFlag', 'locationGroup', 'customerId'
    ];

     * Find or create a customer by name.
     */
    public function findOrCreateByName(array $attributes)
    {
        $customer = self::whereRaw('LOWER(name) = ?', [strtolower($attributes['name'])])->first();

        if ($customer) {
            return $customer;
        }

        return self::create($attributes);
    }

    /**
     * Find or create a customer by ID.
     */
    public function findOrCreateById(int $id, array $attributes)
    {
        $customer = self::find($id);

        if ($customer) {
            return $customer;
        }

        $attributes['id'] = $id;
        return self::create($attributes);
    }
}

