<?php

namespace App\Http\Controllers;

use App\Models\TeamRequest as ModelsTeamRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamRequest extends Controller
{
    //
public function index(){
    $user = auth()->user();
    return view('admin.team.index', compact('user'));

}

public function index1()
{
    $datos = ModelsTeamRequest::all();
    /*$datos = DB::table('request_team')
            ->select('request_team.*')
            ->orderBy('id')
            ->get();*/
    return view('admin.team.record')->with('datos', $datos);
}

public function createTeamRequest(Request $request){
/*dd($request);*/
    $user = auth()->user();
  $request_team = new ModelsTeamRequest();
  $request_team->user_id = $request->user_id;
  $request_team->category = $request->category;
  $request_team->description = $request->description;
  $request_team->status = 'Solicitud enviada';
  $request_team->user_id = $user->id;
  $request_team->save();
  return redirect()->route('team.request')->with('success', '¡Solicitud Creada Exitosamente!');

 
  }


}


