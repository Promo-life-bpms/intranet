<?php

namespace App\Http\Controllers\Communique;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommuniqueController extends Controller
{
    public function __invoke(){
        return view('communique.index');
    }
}
