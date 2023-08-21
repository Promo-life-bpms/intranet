<?php

namespace App\Http\Controllers\Soporte;
use App\Http\Controllers\Controller;
use App\Models\Soporte\UsuariosSoporte;
use Illuminate\Http\Request;

class UsuariosSoporteController extends Controller
{
    //
    public function usuarios(){
        $usuarios = UsuariosSoporte::all();
        $name = $usuarios->name;
        $user = $usuarios->where($name, 'Christian');
    }
}
