<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Find or create a customer based on the type.
     */
    public function findOrCreateCustomer(Request $request)
    {
        $type = $request->input('type');
        $attributes = $request->except('addressName', 'name', 'city', 'zip', 'residentialFlag', 'locationGroup', 'customerId');

        $customer = new Customer();

        if ($type === 'name') {
            $customer = $customer->findOrCreateByName($attributes);
        } elseif ($type === 'id') {
            $id = $request->input('id');
            $customer = $customer->findOrCreateById($id, $attributes);
        } else {
            return response()->json(['message' => 'Invalid type provided.'], 400);
        }

        $addressController = new AddressController();
        $addressController->findOrCreateAddress($request, $customer->id);

        return $customer;
    }
}
