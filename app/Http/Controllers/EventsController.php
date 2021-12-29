<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Event;
use App\Models\NoWorkingDays;
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
        $request->validate([
            'title' => 'required',
            'start'=>'required'
        ]);

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
    public function calendar()
    {

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $eventos = Events::all();

        return view('admin.events.edit', compact('noworkingdays','eventos'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Events  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Events $event)
    {
        return view('admin.events.edit', compact('event'));
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
        $request->validate([
            'title' => 'required',
            'start'=>'required'
        ]);

        $event->update($request->all());

        return redirect()->action([EventsController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Events  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Events $event)
    {
        $event -> delete();
        return redirect()->action([EventsController::class, 'index']);

    }
}
