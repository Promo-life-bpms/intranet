<?php

namespace App\Http\Controllers;

use App\Events\RequestEvent;
use App\Events\RHRequestEvent;
use App\Events\UserEvent;
use App\Exports\DateRequestExport;
use App\Exports\FilterRequestExport;
use App\Exports\RequestExport;
use App\Models\Notification;
use App\Models\NoWorkingDays;
use App\Models\Request as ModelsRequest;
use App\Models\RequestCalendar;
use App\Models\Role;
use App\Models\User;
use App\Models\Vacations;
use App\Notifications\RequestNotification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;


class RequestController extends Controller
{

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
                # code...
                break;
        }
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = RequestCalendar::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($data);
        }

        $myrequests = auth()->user()->employee->yourRequests;

        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }

        $requestDays = RequestCalendar::all();
        $notifications = Notification::all();


        /*         $userDays= RequestCalendar::all()->where('users_id',$id)->unique();
 */
        /*    dd($userDays); */
        return view('request.index', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'requestDays', 'notifications'));
    }

    public function create()
    {
        $id = Auth::id();

        DB::table('request_calendars')->where('requests_id', null)->where('users_id', $id)->delete();

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();

        $vacations = auth()->user()->vacationsAvailables->sum('days_availables');
        $dataVacations  = auth()->user()->vacationsAvailables;

        if ($vacations == null) {
            $vacations = 0;
        }

        return view('request.create', compact('noworkingdays', 'vacations', 'dataVacations'));
    }


    public function store(Request $request)
    {

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $request->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required|max:255',
        ]);



        $id = Auth::id();
        $req = new ModelsRequest();
        $req->employee_id = auth()->user()->employee->id;
        $req->type_request = $request->type_request;
        $req->payment = $request->payment;
        $req->reason = $request->reason;

        $req->direct_manager_id = auth()->user()->employee->jefe_directo_id;
        $req->direct_manager_status = "Pendiente";
        $req->human_resources_status = "Pendiente";
        $req->save();

        $lastRequest = $req->id;
        //Obtiene el id de la solicitud despues de crearla para asignar a la vista del calendario
        // $lastRequest = DB::table('requests')->latest('id')->value('id');
        DB::table('request_calendars')->where('users_id', $id)->where('requests_id', null)->update(['requests_id' => $lastRequest]);

        //Pruebas para validar si el calendario esta vacio

        //Valida que el usuario no envie solicitudes sin dias asignados
        $lastRequestUser = DB::table('request_calendars')->where('users_id', $id)->where('requests_id', $lastRequest)->latest('id')->value('users_id');
        $daysUsed =    DB::table('request_calendars')->where('users_id', $id)->where('requests_id', $lastRequest)->get();
        if ($lastRequestUser == null) {
            $employee_id = DB::table('employees')->where('id', $id)->value('id');
            DB::table('requests')->where('employee_id', $employee_id)->where('id', $lastRequest)->delete();
            return back()->with('message', 'No puedes crear solicitudes por que no agregaste dias en el calendario');
        }
        $daysUsetTotal = count($daysUsed);
        // Restar los dias disponibles
        foreach (auth()->user()->vacationsAvailables as $dataVacation) {
            $diasRestantes = $dataVacation->days_availables - $daysUsetTotal;
            if ($diasRestantes >= 0) {
                $dataVacation->days_availables = $diasRestantes;
                $dataVacation->save();
                break;
            } else {
                $daysUsetTotal = abs($diasRestantes);
                $dataVacation->days_availables = 0;
                $dataVacation->save();
            }
        }

        self::managertNotification($req);

        return redirect()->action([RequestController::class, 'index']);
    }

    public function edit(Request $req, ModelsRequest $request)
    {

        $myrequests = auth()->user()->employee->yourRequests;

        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }

        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();

        return view('request.edit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request'));
    }

    public function update(Request $req, ModelsRequest $request)
    {
        $req->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required'
        ]);

        $request->update($req->all());

        if ($req->direct_manager_status == "Aprobada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::rhNotification($request);
        } elseif ($req->direct_manager_status == "Rechazada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        }


        return redirect()->action([RequestController::class, 'index']);
    }

    public function destroy(ModelsRequest $request)
    {
        DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
    }




    public function authorizeRequestManager()
    {
        /*  $requests = auth()->user()->employee->yourAuthRequests; */

        $id = Auth::user()->id;
        $manager = DB::table('manager')->where('id', $id)->value('users_id');
        $position = DB::table('employees')->where('user_id', $id)->value('position_id');
        $rh = DB::table('positions')->where('id', $position)->value('department_id');

        if ($rh == 1) {
            $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada');
        } else {
            $requests = ModelsRequest::all()->where('direct_manager_id', $id);
        }

        $requestDays = RequestCalendar::all();

        return view('request.authorize', compact('requestDays', 'requests'));
    }

    public function showAll()
    {
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada');
        return view('request.show', compact('requests', 'requestDays'));
    }

    public function reportRequest()
    {
        $vacations = Vacations::all();
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada');
        return view('request.reports', compact('requests', 'requestDays', 'vacations'));
    }


    //Notificaciones 
    static function managertNotification($req)
    {
        event(new RequestEvent($req));
    }

    static function rhNotification($req)
    {
        event(new RHRequestEvent($req));
    }

    static function userNotification($req)
    {
        event(new UserEvent($req));
    }

    public function deleteNotification(ModelsRequest $request)
    {
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();

        return redirect()->action([RequestController::class, 'index']);
    }

    //Autorizaciones de solicitudes de RH y Manager
    public function authorizeEdit(Request $req, ModelsRequest $request)
    {

        $myrequests = auth()->user()->employee->yourRequests;

        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }

        $rhAuth = false;
        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();


        return view('request.edit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request', 'rhAuth'));
    }

    public function authorizeRHEdit(Request $req, ModelsRequest $request)
    {

        $myrequests = auth()->user()->employee->yourRequests;

        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }


        /*    if ($req->human_resources_status == "Aprobada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        } elseif ($req->human_resources_status == "Rechazada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        } */


        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();

        $rhAuth = true;

        return view('request.authorizeEdit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request', 'rhAuth'));



        /* 
        $request->update($req->all());

        if ($req->human_resources_status == "Aprobada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        } elseif ($req->human_resources_status == "Rechazada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        }

        $id = Auth::user()->id;
        $position = DB::table('employees')->where('user_id', $id)->value('position_id');
        $rh = DB::table('positions')->where('id', $position)->value('department_id');

        if ($rh == 1) {
            return redirect()->action([RequestController::class, 'showAll']);
        }
        return redirect()->action([RequestController::class, 'index']); */
    }


    public function authorizeUpdate(Request $req, ModelsRequest $request)
    {
        $req->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required'
        ]);

        $id = Auth::id();

        $request->update($req->all());

        if ($req->human_resources_status == "Aprobada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        } elseif ($req->human_resources_status == "Rechazada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        }
        return redirect()->action([RequestController::class, 'authorizeRequestManager']);
    }




    public function deleteAll(ModelsRequest $request)
    {
        DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
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

        return Excel::download(new FilterRequestExport($start, $end), 'solicitudes_por_periodo.xlsx');



        return view('request.filter', compact('requests', 'requestDays'))->share('start', $start);
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

        /* Excel::download(new DateRequestExport($start,$end,$daySelected), 'solicitudes_por_periodo.xlsx');  */

        return view('request.filterDate', compact('requests', 'requestDays', 'start', 'end'));
    }

    //Exportaciones de excel
    public function export()
    {
        return Excel::download(new RequestExport, 'solicitudes.xlsx');
    }

    public function exportfilter($start, $end)
    {
        return Excel::download(new FilterRequestExport($start, $end), 'solicitudes_por_periodo.xlsx');
    }

    static function exportDataFilter()
    {

        /*         return Excel::download(new DateRequestExport($start,$end,$daySelected), 'solicitudes_por_periodo.xlsx'); 
 */
    }

    public function getDataFilter($data)
    {


        /*  return Excel::download(new DateRequestExport('2022-02-01','2022-02-16',$requestDays), 'solicitudes_por_periodo.xlsx');   */


        return ($data);
    }
}
