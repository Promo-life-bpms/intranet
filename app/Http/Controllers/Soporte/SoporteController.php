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

    public function create()
    {
        $categorias = Categoria::all();
        return view('soporte.create', compact('categorias'));
    }
    public function store(Request $request)
    {
        $id = Auth::user()->id;
        $request->validate([
            'categoria' => 'required',
            'titulo' => 'required',
            'descripcion' => 'required',
            'imagen/link' => 'required',
        ]);


    }
}
