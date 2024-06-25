<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Search for a customer by type and data. If not found, create a new one.
     *
     * @param string $data
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrCreateCustomer($data, $type)
    {
        // Determine the column to search on based on the type
        $column = $this->getColumnFromType($type);

        // Search for the customer
        $customer = DB::table('user_tables')->whereRaw("LOWER($column) = ?", [strtolower($data)])->first();

        if ($customer) {
            return $customer;
        }

        // If customer not found, create a new one
        $newCustomerId = DB::table('user_tables')->insertGetId([
            $column => $data,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('user_tables')->where('id', $newCustomerId)->first();
    }

    /**
     * Get the column name from the type.
     *
     * @param string $type
     * @return string
     */
    private function getColumnFromType($type)
    {
        $columns = [
            'so' => 'so'
        ];

        return $columns[$type] ?? 'so';
    }
}
