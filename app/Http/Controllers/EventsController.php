<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\NoWorkingDays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Events::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($data);
        }


        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }

        return view('admin.events.index', compact('noworkingdays', 'vacations', 'expiration'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        $id = Auth::id();

        switch ($request->type) {
            case 'add':
                $event = Events::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'users_id' => $id
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Events::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'user_id' => $id
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
