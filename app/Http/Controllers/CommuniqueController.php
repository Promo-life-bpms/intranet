<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommuniqueController extends Controller
{
    public function __invoke(){
        return view('communique.index');
    }
}
