<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function findOrCreateCustomer($data, $type)
    {
        if ($type === 'CustomerName') {
            return Customer::findOrCreateByName($data);
        } elseif ($type === 'customerId') {
            return Customer::findOrCreateById($data);
        }

        // Default to find or create by name if type is not recognized
        return Customer::findOrCreateByName($data);
    }
}
