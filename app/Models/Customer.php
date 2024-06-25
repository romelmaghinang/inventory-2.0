<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'so';

    // Define fillable fields if needed
    protected $fillable = [
        'CustomerName',
    ];

    /**
     * Find or create a customer by name.
     */
    public static function findOrCreateByName($name)
    {
        // Perform a case-insensitive search for a customer with the given name
        $customer = DB::table('so')->whereRaw('LOWER(customerId) = ?', [strtolower($name)])->first();

        // If a customer is found, return it
        if ($customer) {
            return $customer;
        }

        // If no customer is found, create a new one
        $newCustomerId = DB::table('so')->insertGetId([
            'customerId' => $name,
        ]);

        // Return the newly created customer
        return DB::table('so')->where('customerId', $newCustomerId)->first();
    }

    /**
     * Find or create a customer by ID.
     */
    public static function findOrCreateById($id)
    {
        $customer = DB::table('so')->find($id);

        if ($customer) {
            return $customer;
        }

        $newCustomerId = DB::table('so')->insertGetId([
            'customerId' => $id,
        ]);

        return DB::table('so')->where('customerId', $newCustomerId)->first();
    }
}

