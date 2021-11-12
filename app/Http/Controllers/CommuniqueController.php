<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use Illuminate\Http\Request;

class CommuniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communiques =  Communique::all();
        $communiquesPrincipal =  Communique::paginate(5);
        return view('communique.index', compact('communiques','communiquesPrincipal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('communique.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'image' => 'required|mimes:png,jpg',
            'description' => 'required'
        ]);
        if (!$request->hasFile("image")) {
            return;
        }
        $imagen = $request->file("image");
        $nombreimagen = $request->title . "." . $imagen->getClientOriginalName();
        $ruta = public_path("img/post/");

        //$imagen->move($ruta,$nombreimagen);
        $imagen->move($ruta, $nombreimagen);
        $request->image = $ruta . $nombreimagen;

        Communique::created($request);
        return redirect()->action([CommuniqueController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function show(Communique $communique)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function edit(Communique $communique)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Communique $communique)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function destroy(Communique $communique)
    {
        //
    }
}
