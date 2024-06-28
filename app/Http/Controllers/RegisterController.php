<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function getRegisterId(Request $request)
    {
        $name = $request->input('name');
        $register = new Register();
        return $register->getRegisterIdByName($name);
    }
}
