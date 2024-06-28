<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function getTaxRateData(Request $request)
    {
        $name = $request->input('taxRateName');
        $tax = new Tax();
        return $tax->getTaxRateIdByName($name);
    }
}
