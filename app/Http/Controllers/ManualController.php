<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function __invoke(){
        
        return view('manual.index');
    }

}
