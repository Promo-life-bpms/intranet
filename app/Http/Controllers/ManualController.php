<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function __invoke(){
        $manual = Manual::all();
        return view('manual.index', compact('manual'));
    }

}
