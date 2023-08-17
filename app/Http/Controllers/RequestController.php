<?php

namespace App\Http\Controllers;

use App\Events\CreateRequestEvent;
use App\Exports\DateRequestExport;
use App\Exports\FilterRequestExport;
use App\Exports\RequestExport;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\NoWorkingDays;
use App\Models\Position;
use App\Models\Request as ModelsRequest;
use App\Models\RequestCalendar;
use App\Models\Role;
use App\Models\User;
use App\Models\Vacations;
use App\Notifications\AlertRequestToAuth;
use App\Notifications\AlertRequestToRH;
use App\Notifications\CreateRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    //Peticiones de los dias del calendario
    public function ajax(Request $request)
    {
        $id = Auth::id();
        $date = RequestCalendar::where('start', $request->start)->where('users_id', $id)->first();
        if ($date != null) {
            return response()->json(['exist' => true]);
        }
        switch ($request->type) {
            case 'add':
                $event = RequestCalendar::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'users_id' => $id,
                    'requests_id' => $request->id,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = RequestCalendar::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'user_id' => $id
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = RequestCalendar::find($request->id)->delete();

                return response()->json($event);
                break;

            default:

                break;
        }
    }

    // Pantalla Inicial
    public function index(Request $request)
    {
        $myrequests = auth()->user()->employee->requestDone()->orderBy('created_at', "DESC")->get();
        return view('request.index', compact('myrequests'));
    }

    public function create()
    {
        // Eliminar los dias selecionados con anterioridad qie no estan ligados a un request
        auth()->user()->daysSelected()->delete();
        // Obtener dias no laborables
        $noworkingdays = NoWorkingDays::orderBy('day')->get();
        // Obtener dias de vacaciones
        $vacations = auth()->user()->employee->take_expired_vacation ? auth()->user()->vacationsComplete()->sum('dv') : auth()->user()->vacationsAvailables()->sum('dv');
        $dataVacations  = auth()->user()->vacationsAvailables()->orderBy('period', 'DESC')->get();
        if ($vacations == null) {
            $vacations = 0;
        }

        $dep = auth()->user()->employee->position->department;
        $positions = Position::all()->where("department_id", $dep)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];
        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                $users["{$emp->user->id}"] = $emp->user->name;
            }
        }


        return view('request.create', compact('noworkingdays', 'vacations', 'dataVacations', 'users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $request->validate([
            'type_request' => 'required',
            'reason' => 'required|max:255',
            'reveal' => 'required',
        ]);

        if ($request->type_request === "Salir durante la jornada") {
            if ($request->start === null && $request->end === null) {
                return back()->with('message', 'Especifica la hora de tu salida o entrada');
            }
        }
        //Valida que el usuario no envie solicitudes sin dias asignados
        if (count(auth()->user()->daysSelected) <= 0) {
            return back()->with('message', 'No puedes crear solicitudes por que no agregaste dias en el calendario');
        }
        $payment = '';
        /*  if ($request->type_request == "Solicitar vacaciones") {
            $payment = 'A cuenta de vacaciones';
        } else {
            $payment = 'Descontar Tiempo/Dia';
        } */
        switch ($request->type_request) {
            case 'Solicitar vacaciones':
                $payment = 'A cuenta de vacaciones';
                break;
            case 'Salir durante la jornada':
                $payment = 'Descontar Tiempo/Dia';
                break;
            case 'Faltar a sus labores':
                $payment = 'Descontar Tiempo/Dia';
                break;
            default:
                $payment = 'Permiso especial';
                break;
        }
        $user = auth()->user();
        $req = new ModelsRequest();
        $req->employee_id = $user->employee->id;
        $req->type_request = $request->type_request;
        $req->payment = $payment;
        $req->reason = $request->reason;
        $req->start = $request->start;
        $req->end = $request->end;
        $req->opcion = $request->opcion;
        $req->doc_permiso = $request->imagenes;
        $req->reveal_id = $request->reveal;

        $req->direct_manager_id = $user->employee->jefe_directo_id;
        $req->direct_manager_status = "Pendiente";
        $req->human_resources_status = "Pendiente";

        $imagenes = $request->file('file');
        if ($imagenes == null) {
            $imagenes = null;
        } else {
            $namesImagenes = [];
            foreach ($imagenes as $imagen) {

                $n = $imagen->getClientOriginalName();
                $nombreImagen = time() . ' ' . Str::slug($n);
                $imagen->move(public_path('storage/images/'), $nombreImagen);
                array_push($namesImagenes, 'storage/images/' . $nombreImagen);
            }  # code...
        }



        $req->doc_permiso = $imagen;

        $req->save();

        //Actualizar el request de los dias seleccionados
        $user->daysSelected()->update(['requests_id' => $req->id]);

        // Enviar notificacion
        /*   $communique_notification = new FirebaseNotificationController();
        $communique_notification->createRequest(strval($user->id));
        $communique_notification->sendToManager(strval($req->direct_manager_id));


        $userReceiver = Employee::find($req->direct_manager_id)->user;
        event(new CreateRequestEvent($req->type_request, $req->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname));
        $userReceiver->notify(new CreateRequestNotification($req->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname)); */

        return redirect()->action([RequestController::class, 'index']);
    }

    public function edit(ModelsRequest $request)
    {
        auth()->user()->daysSelected()->delete();
        // Obtener dias no laborables
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        // Obtener dias de vacaciones
        $vacations = auth()->user()->vacationsAvailables->where('period', '<>', 3)->sum('dv');
        $dataVacations  = auth()->user()->vacationsAvailables()->where('period', '<>', 3)->orderBy('period', 'DESC')->get();
        if ($vacations == null) {
            $vacations = 0;
        }

        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();

        return view('request.edit', compact('noworkingdays', 'vacations', 'daysSelected', 'request', 'dataVacations'));
    }

    public function update(Request $request, ModelsRequest $modelRequest)
    {
        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $request->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required|max:255',
        ]);

        if ($request->type_request === "Salir durante la jornada") {
            if ($request->start === null && $request->end === null) {
                return back()->with('message', 'Especifica la hora de tu salida o entrada');
            }
        }

        $modelRequest->update([
            'type_request' => $request->type_request,
            'payment' => $request->payment,
            'reason' => $request->reason,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        //Actualizar el request de los dias seleccionados
        auth()->user()->daysSelected()->update(['requests_id' => $modelRequest->id]);

        return redirect()->action([RequestController::class, 'index']);
    }

    public function destroy(ModelsRequest $request)
    {
        $request->requestdays()->delete();
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
    }

    public function authorizeRequestManager()
    {
        $requests = auth()->user()->employee->requestToAuth()->orderBy('created_at', 'DESC')->get();
        return view('request.authorize', compact('requests'));
    }

    public function authorizeRequestRH()
    {
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada');
        return view('request.authorize_rh', compact('requests', 'requestDays'));
    }

    public function reportRequest()
    {
        $vacations = Vacations::all();
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada');
        return view('request.reports', compact('requests', 'requestDays', 'vacations'));
    }

    //Vista de excel a exportar
    public function exportAll()
    {
        $vacations = Vacations::all();
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada');
        return view('request.excelReport', compact('requests', 'requestDays', 'vacations'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->whereRaw('DATE(created_at) >= ?', [$request->start])->whereRaw('DATE(created_at) <= ?', [$request->end])->get();

        $start = $request->start;

        $end = $request->end;

        return view('request.filter', compact('requests', 'requestDays', 'start', 'end'));
    }

    public function filterDate(Request $request)
    {
        $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $requestDays = RequestCalendar::all()->where('start', '>=', $request->start)->where('end', '<=', $request->end);
        $daySelected = RequestCalendar::all()->where('start', '>=', $request->start)->where('end', '<=', $request->end)->pluck('requests_id', 'requests_id');

        $requests = ModelsRequest::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->whereIn('id', $daySelected)->get();

        $start = $request->start;
        $end = $request->end;

        return view('request.filterDate', compact('requests', 'requestDays', 'start', 'end'));
    }

    //Exportaciones de excel
    public function export()
    {
        return Excel::download(new RequestExport, 'solicitudes.xlsx');
    }

    public function exportfilter(Request $request)
    {
        return Excel::download(new FilterRequestExport($request->start, $request->end), 'solicitudes_por_periodo.xlsx');
    }


    public function getDataFilter(Request $request)
    {

        $daySelected = RequestCalendar::all()->where('start', '>=', $request->start)->where('end', '<=', $request->end)->pluck('requests_id', 'requests_id');

        $start = $request->start;
        $end = $request->end;

        return  Excel::download(new DateRequestExport($start, $end, $daySelected), 'solicitudes_por_periodo.xlsx');
    }

    // Recordar las solicitudes que estan pendientes a los jefes directos y a rh
    public function alertPendient()
    {
        $request = ModelsRequest::where('direct_manager_status', 'Pendiente')->get();
        $requestRH = ModelsRequest::where('direct_manager_status', '=', 'Aprobada')->where('human_resources_status', 'Pendiente')->get();
        $usersRH = Role::where('name', 'rh')->first()->users;

        foreach ($request as $req) {
            $userReceiver = $req->manager->user;
            $user = $req->employee->user;
            $userReceiver->notify(new AlertRequestToAuth($req->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $req->direct_manager_status));
        }

        if (count($requestRH) > 0) {
            foreach ($usersRH as $userRH) {
                $userRH->notify(new AlertRequestToRH());
            }
        }
    }
}
