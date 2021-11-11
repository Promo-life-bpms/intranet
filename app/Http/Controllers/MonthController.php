<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonthController extends Controller
{
    public function __invoke(){
        return view('month.index');
    }
}
