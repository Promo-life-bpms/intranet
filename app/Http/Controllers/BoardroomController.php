<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use Illuminate\Http\Request;

class BoardroomController extends Controller
{
    public function vista()
    {
        return view('admin.room.dispo');
    }

    public function create()
    {
        
    }

    public function store(Request $request) 
    {
        dd($request);
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'capacitance' => 'required',
            'description' => 'required',
        ]);
 
        $evento = new boardroom();
        $evento->name=$request->input('name');
        $evento->location = $request->input('location');
        $evento->capacitance= $request->input('capacitance');
        $evento->description=$request->input('description');
        $evento->save();
        return redirect()->route([BoardroomController::class, 'vista'])->with('success', 'Evento creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\boardroom  $boardroom
     * @return \Illuminate\Http\Response
     */
    public function show(boardroom $boardroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\boardroom  $boardroom
     * @return \Illuminate\Http\Response
     */
    public function edit(boardroom $boardroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\boardroom  $boardroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, boardroom $boardroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\boardroom  $boardroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(boardroom $boardroom)
    {
        //
    }
}
