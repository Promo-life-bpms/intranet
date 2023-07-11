<?php

namespace App\Http\Controllers;

use App\Http\Livewire\NotifyComponent;
use App\Mail\NotificacionSolicitudes;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\TeamRequest as ModelsTeamRequest;
use App\Models\User;
use App\Notifications\notificacionCorreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TeamRequest extends Controller
{
    //
public function index()
{
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
    return view('admin.team.record')->with('datos', $datos);
}

public function createTeamRequest(Request $request)
{
/*dd($request);*/
    $request->validate([
    'type_of_user' => 'required',
    'area' => 'required',
    'extension'=>'required',
    'immediate_boss'=>'required',
    'company'=>'required',
    'computer_type'=>'required',
    'cell_phone'=>'required',
    'number'=>'required',
    'extension_number'=>'required',
    'equipment_to_use'=>'required',
    'accessories'=>'required',
    'previous_user'=>'required',
    'distribution_and_forwarding'=>'required',
    'others'=>'required',
    'access_to_server_shared_folder'=>'required',
    'folder_path'=>'required',
    'type_of_access'=>'required',
    'observations'=>'required'
    ]);

    // try{
    // DB::beginTransaction();

    $data = [];
     array_push($data,(object)[
        'odoo_users' => json_encode([$request->odoo_users, $request->odoo_users5, $request->odoo_users4, $request->odoo_users3, $request->odoo_users2, $request->odoo_users1 ]),
        'work_profile_in_odoo' => json_encode([$request->work_profile_in_odoo, $request->work_profile_in_odoo5, $request->work_profile_in_odoo4, $request->work_profile_in_odoo3, $request->work_profile_in_odoo2, $request->work_profile_in_odoo1])
     ]);


    $data2 = [];
    array_push($data2,(object)[
        'email' => json_encode([$request->email, $request->email5, $request->email4, $request->email3, $request->email2, $request->email1 ]),
        'signature_or_telephone_contact_numer' => json_encode([$request->signature_or_telephone_contact_numer, $request->signature_or_telephone_contact_numer5, $request->signature_or_telephone_contact_numer4, $request->signature_or_telephone_contact_numer3, $request->signature_or_telephone_contact_numer2, $request->signature_or_telephone_contact_numer1])
    ]);

    $DRH = User::where('id', 6)->first()->name;
    $name = auth()->user()->name;
    $request_team = new ModelsTeamRequest();
    $request_team->type_of_user = $request->type_of_user;
    $request_team->name = $request->jefe_directo_id;
    $request_team->date_admission = $request->date_admission;
    $request_team->area = $request->area;
    $request_team->departament = $request->department;
    $request_team->position = $request->position;
    $request_team->extension = $request->extension;
    $request_team->immediate_boss = $request->immediate_boss;
    $request_team->company = $request->company;
    $request_team->computer_type = $request->computer_type;
    $request_team->cell_phone = $request->cell_phone;
    $request_team->number = $request->number;
    $request_team->extension_number = $request->extension_number;
    $request_team->equipment_to_use = $request->equipment_to_use;
    $request_team->accessories = $request->accessories;
    $request_team->previous_user = $request->previous_user;
    $request_team->email = $data2[0]->email;
    $request_team->signature_or_telephone_contact_numer = $data2[0]->signature_or_telephone_contact_numer;
    $request_team->distribution_and_forwarding = $request->distribution_and_forwarding;
    $request_team->office = $request->office==null?0:1;
    $request_team->acrobat_pdf = $request->acrobat_pdf==null?0:1;
    $request_team->photoshop = $request->photoshop==null?0:1;
    $request_team->premier = $request->premier==null?0:1;
    $request_team->audition = $request->audition==null?0:1;
    $request_team->solid_works = $request->solid_works==null?0:1;
    $request_team->autocad = $request->autocad==null?0:1;
    $request_team->odoo = $request->odoo_checkbox==null?0:1;
    $request_team->odoo_users = $data[0]->odoo_users;
    $request_team->work_profile_in_odoo = $data[0]->work_profile_in_odoo;
    $request_team->others = $request->others;
    $request_team->access_to_server_shared_folder = $request->access_to_server_shared_folder;
    $request_team->folder_path = $request->folder_path;
    $request_team->type_of_access = $request->type_of_access;
    $request_team->observations = $request->observations;
    $request_team->status = 'Solicitud Creada';
    $request_team->save();

    $Recursos =User::where('id', 6)->first()->name;
    $DRH = User::where('id', 6)->first();
    $DRH->notify(new notificacionCorreo($Recursos, $name));

    // DB::commit();
    return redirect()->route('team.request')->with('success', '¡Solicitud Creada Exitosamente!', 'data',$data);
//  }catch(\Exception $e){

    // DB::rollBack();
    // return redirect()->route('team.request')->with('error', 'Ocurrió un error al enviar la solicitud. Por favor, inténtelo de nuevo más tarde.');
}


public function user($id)
{
    $employees = Employee::find($id);
    $data = [
        "date_admission"=> $employees->date_admission->format('Y-m-d'),
        "position"=>$employees->position->name,
        "department"=>$employees->position->department->name
    ];
    return response()->json($data);
}

public function management()
{
    $admon_requests = ModelsTeamRequest::all();
    return view('admin.team.admon')->with('admon_requests', $admon_requests);
}

public function informationrequest($id)
{
    $DRH = User::where('id', 31)->first()->name;
    $name = auth()->user()->name;

    $information_request = ModelsTeamRequest::find($id);

    $Tecnologia_e_innovacion =User::where('id', 31)->first()->name;
    $DRH = User::where('id', 31)->first();
    $DRH->notify(new notificacionCorreo($Tecnologia_e_innovacion, $name));
    // dd($information_request);

     return view('admin.Team.information', compact('information_request'));
}

public function update(Request $request)
{
     $request->validate([
         'status' => 'required',
     ]);
     DB::table('request_for_systems_and_communications_services')->where('id', intval($request->id))->update([
         'status' => $request->status
     ]);
     return redirect()->back()->with('success', '¡Solicitud Actualizada Exitosamente!');
 }
}