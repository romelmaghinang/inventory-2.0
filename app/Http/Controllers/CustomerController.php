<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Search for a customer by name. If not found, create a new one.
     *
     * @param string $customerName
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrCreateCustomer($customerName)
    {
        $customer = DB::table('user_tables')->whereRaw('LOWER(so) = ?', [strtolower($customerName)])->first();

        if ($customer) {
            return $customer;
        }

        $newCustomerId = DB::table('user_tables')->insertGetId([
            'so' => $customerName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('user_tables')->where('id', $newCustomerId)->first();
    }
}

