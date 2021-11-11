<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function __invoke(){

        $access = Access::all();
        return view('access.index', compact('access'));
    }
}
