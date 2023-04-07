<?php

namespace App\Http\Controllers;

use App\Models\prueba;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function index()
    {
        return view('admin.prueba.index');
    }

   
    public function create(Request $request)
    {

        $request->validate([
            'Nombre' => 'required',
            'ApePaterno' => 'required',
            'ApeMaterno' => 'required',
            'NumeroCel' => 'required',
            'Correo'=>'required',
        ]);
 
        $evento = new prueba();
        $evento->Nombre=$request->input('Nombre');
        $evento->ApePaterno = $request->input('ApePaterno');
        $evento->ApeMaterno= $request->input('ApeMaterno');
        $evento->NumeroCel=$request->input('NumeroCel');
        $evento->Correo=$request->input('Correo');
        $evento->save();
        return redirect()->back()->with('message', "Registrado");

    }

   
    
}
