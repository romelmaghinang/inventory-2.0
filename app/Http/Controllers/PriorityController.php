<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function getPriorityId(Request $request)
    {
        $name = $request->input('priorityName');
        $priority = new Priority();
        return $priority->getPriorityIdByName($name);
    }
}
