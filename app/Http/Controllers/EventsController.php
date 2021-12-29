<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $events = Events::all();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $day = $request->start;

        $time = $request->time;
        $datetime = $day . ' ' . $time;

        $events = new Events();
        $events->title = $request->title;
        $events->start = $datetime;
        $events->end = $datetime;
        $events->save();

        return redirect()->action([EventsController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Events $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Events  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Events $event)
    {
        return view('admin.events.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Events  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Events $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Events  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Events $event)
    {
        //
    }
}
