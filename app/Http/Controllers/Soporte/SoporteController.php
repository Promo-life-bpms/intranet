<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Soporte\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoporteController extends Controller
{
    //

   
    public function index()
    {
        return view('soporte.index');
    }

    public function solucion(){
        return view('soporte.solucion');
    }

    public function admin()
    {
        return view('soporte.admin');
    }
}
