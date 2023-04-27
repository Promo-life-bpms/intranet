<?php

namespace App\Http\Controllers;

use App\Models\SeeMore as ModelsSeeMore;
use Illuminate\Http\Request;

class SeeMore extends Controller
{
    public function show($id)
    {
        $users = ModelsSeeMore::find($id);
        /*dd($user);*/
        return view('systems.show', compact('users'));
    }
    
    public function store(Request $request)
    {
        /*$user = auth()->user();
        dd($request);
        $see_more = new ModelsSeeMore();
        $see_more->status = $request->status;
        $see_more->comments = $request->comments;
        $see_more->save();
        return redirect()->route('systems.show')->with('success', '¡Solicitud Aceptada Exitosamente!');*/
    }
}
