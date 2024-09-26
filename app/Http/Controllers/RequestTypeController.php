<?php

namespace App\Http\Controllers;

use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestTypeController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'continuos_days' => 'required',
            'status' => 'required'
        ]);

        RequestType::create([
            'type' => $request->type,
            'description' => $request->description,
            'max_hours_peer_day' => $request->max_hours_peer_day,
            'uses_peer_mont' => $request->uses_peer_mont,
            'continuos_days' => $request->continuos_days,
            'max_continuos_uses' => $request->max_continuos_uses,
            'min_month_time' => $request->min_month_time,
            'comprobation' => $request->comprobation,
            'status' => $request->status
        ]);

        return redirect()->back()->with('message', "Se creó correctamente el tipo de solicitud.");
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'continuos_days' => 'required',
            'status' => 'required',
            'id' => 'required'
        ]);

        $TipoSolicitud = RequestType::where('id', $request->id)->get();

        if(!$TipoSolicitud){
            return redirect()->back()->with('message', "No existe ese tipo de solicitud");
        }
        
        DB::table('request_types')->where('id', $request->id)->update([
            'type' => $request->type,
            'description' => $request->description,
            'max_hours_peer_day' => $request->max_hours_peer_day,
            'uses_peer_mont' => $request->uses_peer_mont,
            'continuos_days' => $request->continuos_days,
            'max_continuos_uses' => $request->max_continuos_uses,
            'min_month_time' => $request->min_month_time,
            'comprobation' => $request->comprobation,
            'status' => $request->status
        ]);
    
        return redirect()->back()->with('message', "Se creó correctamente el tipo de solicitud.");
    }
}
