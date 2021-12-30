<?php

namespace App\Http\Controllers;

use App\Models\NoWorkingDays;
use App\Models\Request as ModelsRequest;
use App\Models\RequestCalendar;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        /*         $userDays= RequestCalendar::all()->where('users_id',$id)->unique();
 */
        /*    dd($userDays); */
        return view('request.index', compact('noworkingdays', 'vacations', 'expiration', 'myrequests', 'requestDays'));
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
        $position = DB::table('employees')->where('user_id',$id)->value('position_id');
        $rh = DB::table('positions')->where('id',$position)->value('department_id');
      
        if($rh==1){
            $requests = ModelsRequest::all()->where('direct_manager_status','Aprobado');
        }else{
            $requests = ModelsRequest::all()->where('direct_manager_id',$id);
        }
        

       
        $requestDays = RequestCalendar::all();

        return view('request.authorize', compact('requestDays','requests'));
    }

    public function showAll()
    {
        $requestDays = RequestCalendar::all();
        $requests = ModelsRequest::all()->where('direct_manager_status','Aprobado');
        return view('request.show', compact('requests','requestDays'));
    }

    public function reportRequest()
    {
        $requests = ModelsRequest::all()->where('direct_manager_status','Aprobado')->where('human_resources_status','Aprobado');
        return view('request.reports', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $id = Auth::id();

        DB::table('request_calendars')->where('requests_id', null)->where('users_id', $id )->delete();

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

        //Obtiene el id de la solicitud despues de crearla para asignar a la vista del calendario
        $lastRequest = DB::table('requests')->latest('id')->value('id');
        $validateRequest= DB::table('request_calendars')->where('users_id', $id)->where('requests_id', null)->update(['requests_id' => $lastRequest]);

        DB::table('request_calendars')->where('users_id', $id)->where('requests_id', null)->update(['requests_id' => $lastRequest]);

        //$validateRequest=RequestCalendar::all()->where('requests_id', $lastRequest)->pluck('id','title');

        if($validateRequest == 0  ){
            //DB::table('requests')->where('users_id', $id)->where('id', $lastRequest )->delete();
            return redirect()->action([RequestController::class, 'index']);
        }


        return redirect()->action([RequestController::class, 'index']);
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
        $request->delete();
        return redirect()->action([RequestController::class, 'authorizeRequestManager']);
    }



    public function export()
    {
        $request = ModelsRequest::all()->toArray();

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setCellValue('A1', '#');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'ID Usuario');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Tipo Solicitud');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'Pago');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'Fecha Ausencia');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'Fecha Reingreso');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'Motivo');
        $spreadsheet->getActiveSheet()->setCellValue('H1', 'ID Jefe');
        $spreadsheet->getActiveSheet()->setCellValue('I1', 'Jefe Status');
        $spreadsheet->getActiveSheet()->setCellValue('J1', 'RH Status');
        $spreadsheet->getActiveSheet()->setCellValue('K1', 'Creado');
        $spreadsheet->getActiveSheet()->setCellValue('L1', 'Ultima modificacion');

        $spreadsheet->getActiveSheet()->fromArray($request, NULL, 'A2');

        $writer = new Xlsx($spreadsheet);
        $writer->save('Solicitudes.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="solicitud.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
