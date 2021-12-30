<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Event;
use App\Models\NoWorkingDays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'start'=>'required',
            'time'=>'required'
        ]);  

        $id = Auth::user()->id;
        $day = $request->start;
        $time = $request->time;
        $datetime = $day . 'T' . $time;

        $events = new Events();
        $events->title = $request->title;
        $events->start = $datetime;
        $events->time = $request->time;
        $events->description = $request->description;
        $events->users_id=$id;
        $events->save();

        return redirect()->action([EventsController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function showEvents()
    {

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $eventos = Events::all();

        return view('admin.events.show', compact('noworkingdays','eventos'));

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
            'start'=>'required',
            'time'=>'required'
        ]);

        $id = Auth::user()->id;
        $day = $request->start;
        $time = $request->time;
        $datetime = $day . 'T' . $time;

      
        $event->title = $request->title;
        $event->start = $datetime;
        $event->time = $request->time;
        $event->description = $request->description;
        $event->users_id=$id;
        $event->save();

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
