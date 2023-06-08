<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\TeamRequest as ModelsTeamRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TeamRequest extends Controller
{
    //
public function index(){
    $user = auth()->user();

    $roles = Role::all();
    $employees = Employee::all();
    $departments  = Department::pluck('name', 'id')->toArray();
    $positions  = Position::pluck('name', 'id')->toArray();
    $companies = Company::all();
    $manager = User::all()->pluck('name', 'id');
    $dates = Employee::all();
    $date_admission = Employee::pluck('date_admission', 'id');
return view('admin.team.index', compact('roles', 'employees', 'departments', 'positions', 'companies', 'manager', 'user', 'dates', 'date_admission'));
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

    $request->validate([
    'category' => 'required',
    'description' => 'required'
    ]);

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

public function user($id){

    $employees = Employee::find($id);

   
    $data = [
        "date_admission"=> $employees->date_admission->format('Y-m-d'),
        "position"=>$employees->position->name,
        "department"=>$employees->position->department->name

    ];
    return response()->json($data);
}

}


