<?php

namespace App\Http\Controllers;

use App\Events\RequestEvent;
use App\Events\RHRequestEvent;
use App\Events\UserEvent;
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
    /**
     * Write code on Method
     *
     * @return response()
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $id = Auth::id();

        DB::table('request_calendars')->where('requests_id', null)->where('users_id', $id)->delete();

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        $vacations = DB::table('vacations_availables')->where('users_id', $id)->value('days_availables');
        $expiration  = DB::table('vacations_availables')->where('users_id', $id)->value('expiration');
        if ($vacations == null) {
            $vacations = 0;
        }

        return view('request.create', compact('noworkingdays', 'vacations', 'expiration'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $request->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required',
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
        if ($lastRequestUser == null) {

            $employee_id = DB::table('employees')->where('id', $id)->value('id');
            DB::table('requests')->where('employee_id', $employee_id)->where('id', $lastRequest)->delete();
            return back()->with('message', 'No puedes crear solicitudes por que no agregaste dias en el calendario');
        }

        self::managertNotification($req);

        return redirect()->action([RequestController::class, 'index']);
    }


    static function managertNotification($req)
    {
        event(new RequestEvent($req));

        /*       $id = Auth::id();
        $manager = DB::table('employees')->where('user_id', $id)->value('jefe_directo_id');
        User::all()->where('id', $manager)->each(function (User $user) use ($req) {
            $user->notify(new RequestNotification($req));
        }); */
    }

    static function rhNotification($req)
    {
        event(new RHRequestEvent($req));
    }

    static function userNotification($req)
    {
        event(new UserEvent($req));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Request  $communique
     * @return \Illuminate\Http\Response
     */
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


        $daysSelected = RequestCalendar::where('requests_id', $request->id)->get();


        return view('request.authorizeEdit', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'daysSelected', 'request'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, ModelsRequest $request)
    {
        $req->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'reason' => 'required'
        ]);

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
        } else {
            return redirect()->action([RequestController::class, 'index']);
        }
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

        if ($request->direct_manager_status == "Aprobada") {

            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();

            self::rhNotification($request);
        } elseif ($req->direct_manager_status == "Rechazada") {
            DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
            self::userNotification($request);
        }
        return redirect()->action([RequestController::class, 'authorizeRequestManager']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelsRequest $request)
    {
        DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
    }


    public function deleteAll(ModelsRequest $request)
    {
        DB::table('request_calendars')->where('requests_id',  $request->id)->delete();
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
    }

    public function deleteNotification(ModelsRequest $request)
    {
        DB::table('notifications')->whereRaw("JSON_EXTRACT(`data`, '$.id') = ?", [$request->id])->delete();

        return redirect()->action([RequestController::class, 'index']);
    }


    public function exportAll()
    {
        $vacations = Vacations::all();
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada');
        return view('request.excelReport', compact('requests', 'requestDays', 'vacations'));
    }

    public function export()
    {
        /* 
        $requestsID =  ModelsRequest::select('id')->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->get()->toArray();

        $requestEmployee = ModelsRequest::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->pluck('employee_id', 'employee_id');
        $requestUser =  User::select('name', 'lastname')->whereIn('id', $requestEmployee)->get()->toArray();
        $requestsType =  ModelsRequest::select('type_request', 'payment')->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->get()->toArray();


        $results = Request::whereIn('employee_id', function ($query) {
            $query->select('id')->from('request')->groupBy('id')->havingRaw('count(*) > 1');
        })->get()->toArray();


        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setCellValue('A1', '#');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nombre');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Apellidos');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'Tipo solicitud');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'Forma de pago');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'Fechas de ausencia');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'Vacaciones restantes');

        $spreadsheet->getActiveSheet()->fromArray($requestsID, NULL, 'A2');

        $spreadsheet->getActiveSheet()->fromArray($results, NULL, 'B2');
        $spreadsheet->getActiveSheet()->fromArray($requestsType, NULL, 'D2');

        $writer = new Xlsx($spreadsheet);
        $writer->save('Solicitudes.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="solicitud.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); */

        return Excel::download(new RequestExport, 'request.xlsx');
    }
}
