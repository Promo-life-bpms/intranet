<?php

namespace App\Http\Controllers;
use App\Models\boardroom;
use App\Models\Department;
use App\Models\Position;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NotificacionEdit;
use App\Notifications\notificacionPJ;
use App\Notifications\notificacionPJEdit;
use App\Notifications\notificacionRH;
use App\Notifications\notificacionRHEdit;
use App\Notifications\NotificacionSalas;
use App\Notifications\notificacionSistemas;
use App\Notifications\notificacionSistemasEdit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReservationController extends Controller
{
    /////////////////////////////////////////////////////MOSTRAR VISTA//////////////////////////////////////////////
    public function index(Request $request)
    {
        $user = auth()->user();
        $salitas = boardroom::all();
        $personas= User::all();
        $boardroom = boardroom::all()->pluck('name', 'id');
        $eventos = Reservation::all();
        $usuarios = User::all();
        $departments  = Department::pluck('name', 'id')->toArray();
        return view('admin.room.index', compact('user','salitas','personas','boardroom','eventos','departments','usuarios'));
    }
    ///////////////////////////////////////////////////////BUSCAR POR DEPARTAMENTOS//////////////////////////////////////
    public function Positions($id)
    {
        $dep = Department::find($id);
        $positions = Position::all()->where("department_id", $id)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];
        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                $users["{$emp->user->id}"] = $emp->user->name;
            }
        }
        return response()->json(['positions' => $positions, 'users' => $users,]);
    }
    /////////////////////////////////////////////FUNCIÓN CREAR EVENTO//////////////////////////////////////////////////
    public function store(Request $request)
    {
        //AUTENTIFICAR AL USUARIO//
        $user = auth()->user();

        //OBTENER LA INFORMACIÓN DEL FORM//
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
        ]);
        //VARIBLES PARA NO CONFUNDIRSE///
        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;

        //OBTENEMOS LOS EVENTOS DEL DÍA//
        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
        //return ($EventosDelDia);

        //OBTENEMOS UN NUEVO ARREGLO DE LOS EVENTOS YA CREADOS PARA PODER CONVERTIR LAS HORAS A MILISEGUNDOS//
        $eventosRefactorizados = [];
        foreach ($EventosDelDia as $item) {
            $componentes = [
                'id' => $item['id'],
                'start' => strtotime($item['start']) * 1000,
                'end' => strtotime($item['end']) * 1000,
                'id_sala' => $item['id_sala']
            ];
            //array_push($eventosRefactorizados, $componentes); otra forma de traer el arreglo nuevo
            $eventosRefactorizados[] = $componentes;
        }
        //dd($eventosRefactorizados);

        //FORMATEAMOS LAS HORAS PARA PODERLAS CONVERTIR A MILISEGUNDOS//
        $inicio = $request->start; // Fecha de inicio del form
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;

        $final = $request->end; //fecha de fin del form
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;
        $fechaActual = now()->format('Y-m-d H:i:s');
        //dd($fechaActual);

        if ($fecha_inicio <= $fechaActual) {
            return redirect()->back()->with('message1', 'No se puede crear una reservación en un día pasado.');  
        }

        if($fecha_termino < $fecha_inicio){
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

        //CONDICIONES QUE DEBE PASAR ANRTES DE EDITAR AL EVENTO// 
        //EL PRIMER FOREACH ES PARA SABER QUE NO TOME TIEMPO DE EVENTOS YA CREADOS//
        foreach ($eventosRefactorizados as $evento) {
            if ($fechaInicio >= $evento['start']-1 && $fechaInicio <= $evento['end']-1) {
                // Si esta dentro del rango
                return redirect()->back()->with('message1', "Ya existe un evento dentro de la hora elegida.");
            }
            if($fechaFinal >= $evento['start']+1 && $fechaFinal <= $evento['end']+1){
                return redirect()->back()->with('message1', "Ya existe un evento dentro de la hora elegida.");
            }
        }

        //EL SEGUNDO FOREACH ES PARA SABER QUE NO SOBRE PASE TIEMPO DE EVENTOS YA CREADOS//
        foreach($eventosRefactorizados as $evento){
            if ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end']) {
                // Si esta dentro del el rango
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
            if ($fechaInicio >= $evento['start'] && $fechaFinal <= $evento['end']){
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
        }
        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invi=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($invi,$usuario->id);
            }   
        }

        //UNA VEZ QUE YA PASO LAS VALIDACIÓNES CREA EL EVENETO//
        $evento = new Reservation();
        $evento->title = $request->title;
        $evento->start = $request->start;
        $evento->end = $request->end;
        $evento->guest = json_encode($invi);
        $evento->department_id=$request->department_id;
        $evento->engrave= $request->engrave;
        $evento->chair_loan= $request->chair_loan;
        $evento->proyector= $request->proyector;
        $evento->description = $request->description;
        $evento->id_usuario = $user->id;
        $evento->id_sala = $request->id_sala;
        $evento->save();

        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        $horainicio= Carbon::parse($request->start)->format('Y-m-d H:i');
        $horafin= Carbon::parse($request->end)->format('Y-m-d H:i');
        //OBTENEMOS LA INFORMACIÓN DE LA SALA//
        $sala= $evento->boordroms->name;
        $ubica=$evento->boordroms->location;
        //AUTENTIFICAMOS AL USUARIO PERO CON SU NOMBRE//
        $name= auth()->user()->name;

        //OBTENEMOS A TODOS LOS USUARIOS DEL FORMULARIO//
        $invitades=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($invitades,$usuario->name);
                $invitados= implode(','.' ',$invitades);   
            }   
        }
        //CORREO PARA LOS INVIDATOS DE LA REUNIÓN//
        $IDs = $invitades;
        foreach($IDs as $invit){
            $usuario->notify(new NotificacionSalas ($name, $invit, $horainicio, $horafin, $ubica, $sala, $request->description));
        }

        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project =User::where('id', 31)->first()->name;
        $informar =User::where('id', 31)->first();
        $informar->notify(new notificacionPJ ($Project, $name, $request->title, $sala,$ubica, $horainicio, $horafin,
                                              $request->engrave,$invitados, $request->chair_loan, $request->proyector,
                                              $request->description));
                                              
        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            $userIDs = [6, 9, 118]; // IDS DEL PERSONAL DE RECURSOS HUMANOS//
            foreach ($userIDs as $userID) {
                $RH = User::where('id', $userID)->first()->name;
                $RecursosHumanos = User::where('id', $userID)->first();
                $RecursosHumanos->notify(new notificacionRH($RH, $name, $sala, $ubica, $horainicio, $horafin, $request->chair_loan, 
                                                                $request->description));
            }
        }
        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS =User::where('id', 127)->first()->name;
            $DS =User::where('id', 127)->first();
            $DS->notify(new  notificacionSistemas ($SISTEMAS, $name, $sala, $ubica, $horainicio, $horafin, $request->proyector,
                                                   $request->description));
        }
        return redirect()->back()->with('message', "Reservación creada correctamente.");
    }
    //////////////////////////////////////////////FUNCIÓN PARA EDITAR/////////////////////////////////////////////////
    public function update(Request $request)
    {
        //INFORMACIÓN QUE DEBE VALIDAR QUE SE ENCUENTRE//
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
        ]);

        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;

        //OBTENEMOS LOS EVENTOS DEL DÍA//
        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
        //dd($start, $end, $eventos->toArray());

        //OBTENEMOS UN NUEVO ARREGLO DE LOS EVENTOS YA CREADOS PARA PODER CONVERTIR LAS HORAS A MILISEGUNDOS//
        $eventosRefactorizados = [];
        foreach ($EventosDelDia as $item) {
            $componentes = [
                'id' => $item['id'],
                'start' => strtotime($item['start']) * 1000,
                'end' => strtotime($item['end']) * 1000,
                'id_sala' => $item['id_sala']

            ];
            //array_push($eventosRefactorizados, $componentes);
            $eventosRefactorizados[] = $componentes;
        }
        //dd($eventosRefactorizados);

        //FORMATEAMOS LAS HORAS PARA PODERLAS CONVERTIR A MILISEGUNDOS//
        $inicio = $request->start; // Fecha de inicio del form
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;

        $final = $request->end; //fecha de fin del form
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;
        $fechaActual = now()->format('Y-m-d H:i:s');   

        if ($fecha_inicio <= $fechaActual) {
            return redirect()->back()->with('message1', 'No se puede crear una reservación en un día pasado.');  
        }

        if($fecha_termino < $fecha_inicio){
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

        //CONDICIONES QUE DEBE PASAR ANRTES DE EDITAR AL EVENTO// 
        //EL PRIMER FOREACH ES PARA SABER QUE NO TOME TIEMPO DE EVENTOS YA CREADOS//
        foreach ($eventosRefactorizados as $evento) {

            if ($fechaInicio >= $evento['start']-1 && $fechaInicio <= $evento['end']-1) {
                return redirect()->back()->with('message1', "Ya existe un evento dentro de la hora elegida.");
            }
            if($fechaFinal >= $evento['start']+1 && $fechaFinal <= $evento['end']-1){
                return redirect()->back()->with('message1', "Ya existe un evento dentro de la hora elegida.");
            }
        }
        //EL SEGUNDO FOREACH ES PARA SABER QUE NO SOBRE PASE TIEMPO DE EVENTOS YA CREADOS//
        foreach($eventosRefactorizados as $evento){
            if ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end']) {
                // Si esta dentro del el rango
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
            if ($fechaInicio >= $evento['start'] && $fechaFinal <= $evento['end']){
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados");
            }
        }
        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invitades=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($invitades,$usuario->id);
                $invitados= implode(','.' ',$invitades);   
            }   
        }

        DB::table('reservations')->where('id', $request->id_evento)->update([
            'title' => $request->title, 'start' => $request->start,
            'end' => $request->end, 'department_id'=>$request->department_id,
            'guest' => json_encode($invitades),'engrave' => $request->engrave,
            'chair_loan' => $request->chair_loan,'proyector' => $request->proyector, 
            'description' => $request->description, 'id_sala' => $request->id_sala
        ]);
            
        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        $horainicio= Carbon::parse($request->start)->format('Y-m-d H:i');
        $horafin= Carbon::parse($request->end)->format('Y-m-d H:i');
        //OBTENEMOS LA SALA//
        $sala= $request->id_sala;
        $ubica = boardroom::where('id', $sala)->value('location');
        $names = boardroom::where('id', $sala)->value('name');
        $name= auth()->user()->name;

        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invitades=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($invitades,$usuario->name);
                $asistentes= implode(','.' ',$invitades);   
            }   
        }
        //CORREO PARA LOS INVIDATOS DE LA REUNIÓN//
        $IDs = $invitades;
        foreach($IDs as $invit){
            $usuario->notify(new NotificacionEdit ($name, $invit, $horainicio, $horafin, $ubica, $sala, $request->description));
        }

        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project =User::where('id', 31)->first()->name;
        $informar =User::where('id', 31)->first();
        $informar->notify(new notificacionPJEdit ($Project, $name, $request->title, $names,$ubica, $horainicio, $horafin,
                                                  $request->engrave,$asistentes, $request->chair_loan, $request->proyector,
                                                  $request->description));
                                                  
        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            $userIDs = [6, 9, 118]; // Agrega los IDs de usuario adicionales que deseas obtener
            foreach ($userIDs as $userID) {
                $RH = User::where('id', $userID)->first()->name;
                $RecursosHumanos = User::where('id', $userID)->first();
                $RecursosHumanos->notify(new notificacionRHEdit($RH, $name, $names, $ubica, $horainicio, $horafin, $request->chair_loan, 
                                                                $request->description));
            }
        }
            
        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS =User::where('id', 127)->first()->name;
            $DS =User::where('id', 127)->first();
            $DS->notify(new  notificacionSistemasEdit ($SISTEMAS, $name, $names, $ubica, $horainicio, $horafin, $request->proyector,
                                                       $request->description));
        }

        return redirect()->back()->with('message2', "Evento editado correctamente.");
    }
    //////////////////////////////////////////////FUNCIÓN ELIMINAR///////////////////////////////////////////////////
    public function destroy(Request $request)
    {
        DB::table('reservations')->where('id', $request->id_evento)->delete();
        return redirect()->back()->with('message1', 'Evento eliminado.');
    }
    /////////////////////////////////////////////MOSTRAR EVENTOS////////////////////////////////////////////////////
    public function view(Reservation $reservation)
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
}