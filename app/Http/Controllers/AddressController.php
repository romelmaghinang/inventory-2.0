<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function findOrCreateAddress(Request $request, int $customerId)
    {
        $attributes = $request->only([
            'addressName', 'name', 'city', 'zip', 'residentialFlag', 'locationGroup', 'customerId'
        ]);

        return Address::findOrCreateByCustomerId($customerId, $attributes);
    }
}
