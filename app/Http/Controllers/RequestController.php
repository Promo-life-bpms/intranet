<?php

namespace App\Http\Controllers;

use App\Events\RequestEvent;
use App\Events\RHRequestEvent;
use App\Events\UserEvent;
use App\Exports\DateRequestExport;
use App\Exports\FilterRequestExport;
use App\Exports\RequestExport;
use App\Mail\rejectedRequestMail;
use App\Mail\RequestMail;
use App\Models\Employee;
use App\Models\NoWorkingDays;
use App\Models\Request as ModelsRequest;
use App\Models\RequestCalendar;
use App\Models\RequestRejected;
use App\Models\Vacations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

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
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        // Obtener dias de vacaciones
        $vacations = auth()->user()->vacationsAvailables->where('period', '<>', 3)->sum('dv');
        $dataVacations  = auth()->user()->vacationsAvailables()->where('period', '<>', 3)->orderBy('period', 'DESC')->get();

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

        if ($request->type_request === "Salir durante la jornada") {
            if ($request->start === null && $request->end === null) {
                return back()->with('message', 'Especifica la hora de tu salida o entrada');
            }
        }
        //Valida que el usuario no envie solicitudes sin dias asignados
        if (count(auth()->user()->daysSelected) <= 0) {
            return back()->with('message', 'No puedes crear solicitudes por que no agregaste dias en el calendario');
        }

        $req = new ModelsRequest();
        $req->employee_id = auth()->user()->employee->id;
        $req->type_request = $request->type_request;
        $req->payment = $request->payment;
        $req->reason = $request->reason;
        $req->start = $request->start;
        $req->end = $request->end;

        $req->direct_manager_id = auth()->user()->employee->jefe_directo_id;
        $req->direct_manager_status = "Pendiente";
        $req->human_resources_status = "Pendiente";

        $req->save();

        //Actualizar el request de los dias seleccionados
        auth()->user()->daysSelected()->update(['requests_id' => $req->id]);

        // Enviar notificacion


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

    public function show()
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
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('dv');
        $expiration  = DB::table('employees')->where('id', $id)->value('date_admission');
        if ($vacations == null) {
            $vacations = 0;
        }

        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();

        return view('request.authorizeEdit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request'));
    }

    public function authorizeRHEdit(Request $req, ModelsRequest $request)
    {

        $myrequests = auth()->user()->employee->yourRequests;

        $id = Auth::id();
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('dv');
        $expiration  = DB::table('employees')->where('id', $id)->value('date_admission');
        if ($vacations == null) {
            $vacations = 0;
        }

        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();

        $rhAuth = true;

        return view('request.rhEdit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request', 'rhAuth'));
    }

    public function authorizeManagerUpdate(Request $req, ModelsRequest $request)
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

            try {
                self::sendRejectedMail($request, $req);

                throw new Exception();
            } catch (Exception $e) {
            } finally {
                $rejected = DB::table('request_calendars')->where('requests_id', $request->id)->get();

                //Pasa las fechas de las solicitudes rechazadas a otra tabla
                foreach ($rejected  as $rej) {

                    $data = new RequestRejected();
                    $data->title = $rej->title;
                    $data->start = $rej->start;
                    $data->end = $rej->end;
                    $data->users_id = $rej->users_id;
                    $data->requests_id = $rej->requests_id;
                    $data->save();
                }


                DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
                DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
                self::userNotification($request);
            }
        }

        return redirect()->action([RequestController::class, 'authorizeRequestManager']);
    }


    public function authorizeRHUpdate(Request $req, ModelsRequest $request)
    {
        //$req = datos que recibe de la vista
        //$request = datos previos
        $req->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required'
        ]);

        $id = Auth::id();

        $request->update($req->all());

        if ($req->human_resources_status == "Aprobada") {

            try {
                self::sendApprovedMail($request, $req);
                throw new Exception();
            } catch (Exception $e) {
            } finally {
                DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
                self::userNotification($request);

                //Actualiza dias de periodo cumplidos
                $approved = DB::table('request_calendars')->where('requests_id', $request->id)->get();
                $total = count($approved);

                if ($request->type_request == "Solicitar vacaciones") {
                    $user = Employee::find($request->employee_id)->user;
                    foreach ($user->vacationsAvailables()->orderBy('period', 'DESC')->get() as $dataVacation) {
                        // dd($total);
                        if ($dataVacation->dv < $total) {
                            $dataVacation->days_enjoyed = $dataVacation->dv;
                            $total = $total - $dataVacation->dv;
                            $dataVacation->dv = 0;
                            $dataVacation->save();
                        } else {
                            $dataVacation->days_enjoyed = $dataVacation->days_enjoyed + $total;
                            $dataVacation->dv = $dataVacation->dv - $total;
                            $dataVacation->save();
                            break;
                        }
                    }
                }
            }
        } elseif ($req->human_resources_status == "Rechazada") {

            try {
                self::sendRejectedMail($request, $req);
                throw new Exception();
            } catch (Exception $e) {
            } finally {
                //Actualiza DV
                $rejected = DB::table('request_calendars')->where('requests_id', $request->id)->get();

                //Pasa las fechas de las solicitudes rechazadas a otra tabla
                foreach ($rejected  as $rej) {

                    $data = new RequestRejected();
                    $data->title = $rej->title;
                    $data->start = $rej->start;
                    $data->end = $rej->end;
                    $data->users_id = $rej->users_id;
                    $data->requests_id = $rej->requests_id;
                    $data->save();
                }

                DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
                DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
                self::userNotification($request);
            }
        }
        return redirect()->action([RequestController::class, 'show']);
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

    public function getPayment($id)
    {
        if ($id == "Solicitar vacaciones") {
            return response()->json(['name' => 'A cuenta de vacaciones', 'display' => 'false']);
        } else if ($id == "Salir durante la jornada") {
            return response()->json(['name' => 'Descontar Tiempo/Dia', 'display' => 'true']);
        } else {
            return response()->json(['name' => 'Descontar Tiempo/Dia', 'display' => 'false']);
        }
    }

    public function sendApprovedMail($request, $req)
    {
        //envio de correos
        $mail = DB::table('users')->where('id', $request->employee_id)->value('email');
        $mailInfo = $req;
        $username = DB::table('users')->where('id', $request->employee_id)->value('name');
        $days = DB::table('request_calendars')->where('requests_id', $request->id)->get('start');
        $daysSelected = '';

        foreach ($days as $day) {
            $daysSelected  = $daysSelected . ', ' . $day->start;
        }

        $mailInfo = [
            'name' => $username,
            'type_request' => $request->type_request,
            'reason' => $request->reason,
            'payment' => $request->payment,
            'start' =>  $request->start,
            'end' =>  $request->end,
            'days' => $daysSelected
        ];

        Mail::to($mail)->send(new RequestMail($mailInfo));
    }

    public function sendRejectedMail($request, $req)
    {
        //envio de correos rechazados
        $mail = DB::table('users')->where('id', $request->employee_id)->value('email');
        $mailInfo = $req;
        $username = DB::table('users')->where('id', $request->employee_id)->value('name');
        $days = DB::table('request_calendars')->where('requests_id', $request->id)->get('start');
        $daysSelected = '';

        foreach ($days as $day) {
            $daysSelected  = $daysSelected . ', ' . $day->start;
        }

        $mailInfo = [
            'name' => $username,
            'type_request' => $request->type_request,
            'reason' => $request->reason,
            'payment' => $request->payment,
            'start' =>  $request->start,
            'end' =>  $request->end,
            'days' => $daysSelected
        ];

        Mail::to($mail)->send(new rejectedRequestMail($mailInfo));
    }
}
