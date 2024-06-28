<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    public function getSalesmanId(Request $request)
    {
        $salesmanId = $request->input('salesmanId');
        $salesman = new Salesman();
        return $salesman->getSalesmanIdByName($salesmanId);
    }
    public function getSalesmanData(Request $request)
    {
        $name = $request->input('salesmanName');
        $salesman = new Salesman();
        return $salesman->getSalesmanDataByName($name);
    }
}
