<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'user_tables';

    // Define fillable fields if needed
    protected $fillable = [
        'CustomerName',
    ];

    /**
     * Find or create a customer by name.
     */
    public static function findOrCreateByName($name)
    {
        $customer = DB::table('user_tables')->whereRaw('LOWER(so) = ?', [strtolower($name)])->first();

        if ($customer) {
            return $customer;
        }

        $newCustomerId = DB::table('user_tables')->insertGetId([
            'CustomerName' => $name,
        ]);

        return DB::table('user_tables')->where('customerId', $newCustomerId)->first();
    }

    /**
     * Find or create a customer by ID.
     */
    public static function findOrCreateById($id)
    {
        $customer = DB::table('user_tables')->find($id);

        if ($customer) {
            return $customer;
        }

        $newCustomerId = DB::table('user_tables')->insertGetId([
            'customerId' => $id,
        ]);

        return DB::table('user_tables')->where('customerId', $newCustomerId)->first();
    }
}

