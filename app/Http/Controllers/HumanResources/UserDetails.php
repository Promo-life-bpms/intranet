<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDetails extends Controller
{
    //
    public function moreInformation ($id)
    {
        # code...
        return view('rh.more-information');
    }

    public function createMoreInformation(Request $request)
    {
        # code...
    }
}
