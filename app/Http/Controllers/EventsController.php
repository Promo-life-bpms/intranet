<?php

namespace App\Http\Controllers;

use App\Models\Events;
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
        

        if($request->ajax()) {
            
             $data = Events::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['id', 'title', 'start', 'end']);
  
             return response()->json($data);
        }
  
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();

        return view('admin.events.index', compact('noworkingdays'));
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
 
        switch ($request->type) {
           case 'add':
              $event = Events::create([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($event);
             break;
  
           case 'update':
              $event = Events::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Events::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}
