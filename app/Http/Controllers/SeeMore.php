<?php

namespace App\Http\Controllers;

use App\Models\SeeMore as ModelsSeeMore;
use Illuminate\Http\Request;

class SeeMore extends Controller
{
    public function show($id)
    {
        $user = ModelsSeeMore::find($id);
        /*dd($user);*/
        return view('systems.show', compact('user'));
    }
    
}
