<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use Illuminate\Http\Request;

class BoardroomController extends Controller
{
    public function vista()
    {
        return view('admin.room.index');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
