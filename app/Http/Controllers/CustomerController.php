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
        $name = $request->input('name');
        $addressName = $request->input('addressName');
        $customerName = $request->input('customerName');
        $city = $request->input('city');
        $state = $request->input('state');
        $zip = $request->input('zip');
        $isResidential = $request->input('residentialFlag');
        $customerGroup = $request->input('locationGroup');

        if ($type === 'name') {
            return Customer::findOrCreateByName($name, $addressName, $customerName, $city, $state, $zip, $isResidential, $customerGroup);
        } elseif ($type === 'id') {
            $id = $request->input('id');
            return Customer::findOrCreateById($id, $name, $addressName, $customerName, $city, $state, $zip, $isResidential, $customerGroup);
        }

        return response()->json(['message' => 'Invalid type provided.'], 400);
    }
}
