<?php

namespace App\Http\Controllers;

use App\Models\TeamRequest as ModelsTeamRequest;
use Illuminate\Http\Request;

class TeamRequest extends Controller
{
    //
public function index(){
    $user = auth()->user();
    
    return view('admin.team.index', compact('user'));

}

public function index1(){
    return view('admin.team.record');
}

public function createTeamRequest(Request $request){
/*dd($request);*/
    $user = auth()->user();
  $request_team = new ModelsTeamRequest();
  $request_team->user_id = $request->user_id;
  $request_team->category = $request->category;
  $request_team->description = $request->description;
  $request_team->status = $request->status;
  $request_team->user_id = $user->id;
  $request_team->save();
  return redirect()->route('team.request');
}

}
