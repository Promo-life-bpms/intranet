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
use App\Notifications\NotificacionReservaMasiva;
use App\Notifications\notificacionRH;
use App\Notifications\notificacionRHEdit;
use App\Notifications\NotificacionSalas;
use App\Notifications\notificacionSistemas;
use App\Notifications\notificacionSistemasEdit;
use App\Notifications\NotificacionReservaMasivaEdit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager;


class ReservationController extends Controller
{
    /////////////////////////////////////////////////////MOSTRAR VISTA//////////////////////////////////////////////
    public function index(Request $request)
    {
        $user = auth()->user();
        $salitas = boardroom::all();
        $boardroom = boardroom::all()->pluck('name', 'id');
        $eventos = Reservation::all();
        $guests = $eventos->pluck('guest');
        $departments  = Department::pluck('name', 'id')->toArray();
        $nombresInvitados = Reservation::whereIn('guest', $guests)->get();
        $arreglon = [];
        foreach ($nombresInvitados as $invitados) {
            $arreglo = explode(',', $invitados->guest);
            $arreglon[] = $arreglo;
        }

        $nameusers = [];
        foreach ($arreglon as $nombresusuarios) {
            $nombres = [];
            foreach ($nombresusuarios as $id) {
                $user = User::where('id', $id)->first();
                if ($user) {
                    $nombre = $user->name;
                    $apellido = $user->lastname;
                    $nombres[] = "$nombre $apellido";
                } else {
                    $nombres[] = "Usuario no encontrado";
                }
            }
            $nameusers[] = $nombres;
        }
        //OBTENCIÓN DE LOS JEFES DE CADA ÁREA (GERENTES)//
        $managers = Manager::all();
        $gerentes = [];
        foreach ($managers as $manager) {
            $user = User::find($manager->users_id);
            if ($user) {
                $gerentes[$user->id] = $user->id;
            }
        }
       // dd($gerentes);    
        return view('admin.room.index', compact('user', 'salitas', 'boardroom', 'eventos', 'departments', 'nameusers', 'gerentes'));
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
                $users["{$emp->user->id}"] = $emp->user->name . ' ' . $emp->user->lastname;
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
        //dd($request->reservation);
        // dd($request);
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
            return redirect()->back()->with('message1', 'No se puede crear una reservación en una fecha pasada.');
        }

        if ($fecha_termino < $fecha_inicio) {
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

        $allreservation = Reservation::where('id_usuario', $user->id)
            ->where('start', '>=', $request->start)
            ->where('end', '<=', $request->end)
            ->exists();
        if ($allreservation) {
            return redirect()->back()->with('message1', 'No puedes reservar todas las salas a la misma fecha y hora.');
        }
        
        $id_sala = boardroom::all();
        $gerentes = Reservation::where('start','<=', $fecha_inicio)
                                ->where('end','>=', $fecha_termino)
                                ->where('reservation', 'Sí')
                                ->where('id_sala', $id_sala)
                                ->exists();                 
        if ($gerentes) {
            return redirect()->back()->with('message1', 'Un gerente reservo toda la sala, por lo tanto no puedes crear un evento en esta fecha y hora.');
        }

        //CONDICIONES QUE DEBE PASAR ANRTES DE EDITAR AL EVENTO// 
        foreach ($eventosRefactorizados as $evento) {
            if (($fechaInicio >= $evento['start'] && $fechaInicio < $evento['end']) ||
                ($fechaFinal > $evento['start'] && $fechaFinal <= $evento['end']) ||
                ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end'])
            ) {
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
        }
        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invi = [];
        $usuarios = User::all();
        foreach ($usuarios as $usuario) {
            if ($request->has('guest' . strval($usuario->id))) {
                array_push($invi, $usuario->id);
                $invitados = implode(',', $invi);
            }
        }

        //UNA VEZ QUE YA PASO LAS VALIDACIÓNES CREA EL EVENETO//
        $evento = new Reservation();
        $evento->title = $request->title;
        $evento->start = $request->start;
        $evento->end = $request->end;
        $evento->guest = $invitados;
        $evento->engrave = $request->engrave;
        $evento->chair_loan = $request->chair_loan;
        $evento->proyector = $request->proyector;
        $evento->description = $request->description;
        $evento->reservation = $request->reservation;
        $evento->id_usuario = $user->id;
        $evento->id_sala = $request->id_sala;
        $evento->save();

        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        setlocale(LC_TIME, 'es_ES');
        $diaInicio = Carbon::parse($request->start)->format('d');
        $MesInicio = Carbon::parse($request->start)->format('m');
        $LInicio = strftime('%B', mktime(0, 0, 0, $MesInicio, 1));
        $HoraInicio = Carbon::parse($request->start)->format('H:i');

        $diaFin = Carbon::parse($request->end)->format('d');
        $MesFin = Carbon::parse($request->end)->format('m');
        $LFin = strftime('%B', mktime(0, 0, 0, $MesFin, 1));
        $HoraFin = Carbon::parse($request->end)->format('H:i');

        //OBTENEMOS LA INFORMACIÓN DE LA SALA//
        $sala = $evento->boordroms->name;
        $ubica = $evento->boordroms->location;

        //AUTENTIFICAMOS AL USUARIO PERO CON SU NOMBRE//
        $name = auth()->user()->name;

        //OBTENEMOS A TODOS LOS USUARIOS DEL FORMULARIO//
        $invitades = [];
        $usuarios = User::all();
        foreach ($usuarios as $usuario) {
            if ($request->has('guest' . strval($usuario->id))) {
                array_push($invitades, $usuario->name);
                $invitados = implode(',' . ' ', $invitades);
            }
        }
        foreach ($invitades as $invitado) {
            $user = User::where('name', $invitado)->first();
            if ($user) {
                $user->notify(new NotificacionSalas($name, $invitado, $diaInicio, $LInicio, $HoraInicio, $diaFin, $LFin,
                                                    $HoraFin, $ubica, $sala, $request->description));
            }
        }

        //CORREOS MASIVOS CUANDO UN GERENTE RESERVA TODA LA SALA//
        if ($request->reservation == 'Sí') {
            $users = User::all();
            foreach ($users as $user) {
                if ($user->id == 32) {
                    $nombre = User::where('id', $user->id)->pluck('name')->first();
                    $user->notify(new NotificacionReservaMasiva($name, $nombre, $sala, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin ));
                    break;
                }
            }
        }
        
        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project = User::where('id', 31)->first()->name;
        $informar = User::where('id', 31)->first();
        $informar->notify(new notificacionPJ($Project, $name, $request->title, $sala, $ubica, $diaInicio, $LInicio,
                                             $HoraInicio, $diaFin, $LFin, $HoraFin, $request->engrave, $invitados,
                                             $request->chair_loan, $request->proyector,$request->description
        ));

        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            //$userIDs =Department::all()->pluck('id'); // IDs DE RECURSOS HUMANOS//
            $dep = Department::find(1);
            $positions = Position::all()->where("department_id", 1)->pluck("name", "id");
            $data = $dep->positions;
            $users = [];
            foreach ($data as $dat) {
                foreach ($dat->getEmployees as $emp) {
                    $users["{$emp->user->id}"] = $emp->user->id;
                }
            }
            foreach ($users as $userID) {
                if($userID==6){
                    $RH= User::where('id', $userID)->first()->name;
                    $RecursosHumanos = User::where('id', $userID)->first();
                    $RecursosHumanos->notify(new notificacionRH($RH, $name, $sala, $ubica, $diaInicio,$LInicio,$HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
                    break;
                }                                                 
            }
            //AQUÍ SE PUEDE AGREGAR EL CORREO DE ALGÚN OTRO COLABORADOR QUE NO SEA DE RH//
            $AD= User::where('id', 147)->first()->name;
            $ADMINISTRACION = User::where('id', 147)->first();
            $ADMINISTRACION->notify(new notificacionRH($AD, $name, $sala, $ubica, $diaInicio,$LInicio,$HoraInicio, 
                                                        $diaFin, $LFin, $HoraFin, $request->chair_loan, $request->description));
        }
        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS =User::where('id', 127)->first()->name;
            $DS =User::where('id', 127)->first();

            $DS->notify(new  notificacionSistemas ($SISTEMAS, $name, $sala, $ubica,$diaInicio,$LInicio,$HoraInicio, 
                                                   $diaFin, $LFin, $HoraFin, $request->proyector, $request->description));
        }
        return redirect()->back()->with('message', "Reservación creada correctamente.");
    }
    //////////////////////////////////////////////FUNCIÓN PARA EDITAR/////////////////////////////////////////////////
    public function update(Request $request)
    {

        $user = auth()->user();
        // INFORMACIÓN QUE DEBE VALIDAR QUE SE ENCUENTRE //
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
        ]);
        //dd($request);
        $fecha_inicio = $request->start;
        $fecha_termino = $request->end;

        // OBTENEMOS LOS EVENTOS DEL DÍA //
        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)
            ->get();

        // OBTENEMOS UN NUEVO ARREGLO DE LOS EVENTOS YA CREADOS PARA PODER CONVERTIR LAS HORAS A MILISEGUNDOS //
        $eventosRefactorizados = [];
        foreach ($EventosDelDia as $item) {
            if ($item['id'] != $request->id_evento) {
                $componentes = [
                    'id' => $item['id'],
                    'start' => strtotime($item['start']) * 1000,
                    'end' => strtotime($item['end']) * 1000,
                    'id_sala' => $item['id_sala']
                ];
                $eventosRefactorizados[] = $componentes;
            }
        }
        // FORMATEAMOS LAS HORAS PARA PODERLAS CONVERTIR A MILISEGUNDOS //
        $inicio = $request->start; // Fecha de inicio del form
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;

        $final = $request->end; // fecha de fin del form
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;
        $fechaActual = now()->format('Y-m-d H:i:s');
        //dd($fechaActual);

        if ($fecha_inicio <= $fechaActual) {
            return redirect()->back()->with('message1', 'No se puede editar una reservación de una fecha pasa o elegir una fecha pasada.');
        }

        if ($fecha_termino < $fecha_inicio) {
            return redirect()->back()->with('message1', "Una reservación no puede finalizar antes que la hora de inicio.");
        }

         
        // CONDICIONES QUE DEBE PASAR ANTES DE EDITAR EL EVENTO //
        foreach ($eventosRefactorizados as $evento) {
            if (($fechaInicio >= $evento['start'] && $fechaInicio < $evento['end']) ||
                ($fechaFinal > $evento['start'] && $fechaFinal <= $evento['end']) ||
                ($fechaInicio <= $evento['start'] && $fechaFinal >= $evento['end'])
            ) {
                return redirect()->back()->with('message1', "El evento no puede tomar horas de otros eventos ya creados.");
            }
        } 
        
        $gerentes = Reservation::where('start','<=', $fecha_inicio)
                                ->where('end','>=', $fecha_termino)
                                ->where('reservation', 'Sí')
                                ->exists();                 
        if ($gerentes) {
            return redirect()->back()->with('message1', 'Un gerente reservo toda la sala, por lo tanto no puedes editar el evento en esta fecha y hora.');
        }
        
        $event = Reservation::find($request->id_evento);
        if (!$event) {
            return redirect()->back()->with('message1', 'El evento que intentas editar no existe.');
        }
       
        $allReservation = Reservation::where('id_usuario', $user->id)
                                       ->where('start', '>=', $request->start)
                                       ->where('end', '<=', $request->end)
                                       ->where('id', '!=', $request->id_evento)
                                       ->exists();
        if ($allReservation) {
            return redirect()->back()->with('message1', 'No puedes reservar todas las salas a la misma fecha y hora.');
        }

        // AGREGAMOS LOS NUEVOS USUARIOS AL VIEJO ARREGLO //
        $invitadospos = DB::table('reservations')
            ->select('guest')
            ->where('id', $request->id_evento)
            ->first();

        $invitades = [];
        $usuarios = User::all();
        foreach ($usuarios as $usuario) {
            if ($request->has('guest' . strval($usuario->id))) {
                $invitades[] = $usuario->id;
            }
        }
        if ($invitadospos) {
            $invitades = array_merge($invitades, [$invitadospos->guest]);
        }
        $invitados = implode(',', $invitades);

        // HACEMOS LA ACTUALIZACIÓN DE LA BASE DE DATOS //
        DB::table('reservations')->where('id', $request->id_evento)->update([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'guest' => $invitados,
            'engrave' => $request->engrave,
            'chair_loan' => $request->chair_loan,
            'proyector' => $request->proyector,
            'description' => $request->description,
            'reservation' => $request->reservation,
            'id_sala' => $request->id_sala
        ]);

        //OBTENCIÓN DE INFORMACIÓN PARA ENVIAR LOS CORREOS//
        //LE DAMOS FORMATO A LAS FECHAS//
        setlocale(LC_TIME, 'es_ES');
        $diaInicio= Carbon::parse($request->start)->format('d');
        $MesInicio = Carbon::parse($request->start)->format('m');
        $LInicio = strftime('%B', mktime(0, 0, 0, $MesInicio, 1));
        $HoraInicio = Carbon::parse($request->start)->format('H:i');

        $diaFin= Carbon::parse($request->end)->format('d');
        $MesFin= Carbon::parse($request->end)->format('m');
        $LFin = strftime('%B', mktime(0, 0, 0, $MesFin, 1));
        $HoraFin= Carbon::parse($request->end)->format('H:i');

        //OBTENEMOS LA SALA//
        $sala= $request->id_sala;
        $ubica = boardroom::where('id', $sala)->value('location');
        $names = boardroom::where('id', $sala)->value('name');

        //AUTWNTIFICAMOS AL USUARIO POR SU NOMBRE//
        $name= auth()->user()->name;

        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $invitadospos = DB::table('reservations')
                ->where('id', $request->id_evento)
                ->get();
        $array = explode(',', $invitadospos[0]->guest);

        //OBTENEMOS LOS NOMBRES DE LOS USUARIOS DENTRO DEL ARREGLO//
        $nombres = [];  
        foreach ($array as $usuario) {
            $nombre= User::where('id', $usuario)->first()->name; 
            $nombres[] = $nombre;  
            $guest= implode(',',$nombres);
        }
        //CORREO PARA LOS INVIDATOS DE LA REUNIÓN//
        foreach($array as $invitado){
            $nombre= User::where('id', $invitado)->first()->name;
            $notificacion = User::where('id', $invitado)->first();
            $notificacion->notify(new NotificacionEdit ($name, $nombre, $diaInicio,$LInicio,$HoraInicio, $diaFin, $LFin, 
                                                   $HoraFin, $ubica, $names, $request->description));
        }

        if ($request->reservation == 'Sí') {
            $users = User::all();
            foreach ($users as $user) {
                if ($user->id == 32) {
                    $nombre = User::where('id', $user->id)->pluck('name')->first();
                    $user->notify(new NotificacionReservaMasivaEdit($name, $nombre, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, 
                                                                $diaFin, $LFin, $HoraFin ));
                    break;
                }
            }
        }

        //CORREO PARA EL DEPARTAMENTO DE PROJECT MANAGER//
        $Project =User::where('id', 31)->first()->name;
        $informar =User::where('id', 31)->first();
        $informar->notify(new notificacionPJEdit ($Project, $name, $request->title, $names,$ubica,$diaInicio,$LInicio,$HoraInicio, 
                                                  $diaFin, $LFin, $HoraFin,$request->engrave,$guest, $request->chair_loan, 
                                                  $request->proyector,$request->description));
                                                  
        //CORREO PARA EL DEPARTAMENTO DE RECURSOS HUMANOS PARA MATERIAL (SILLAS)//
        if ($request->chair_loan > 0) {
            //$userIDs =Department::all()->pluck('id'); // IDs DE RECURSOS HUMANOS//
            $dep = Department::find(1);
            $positions = Position::all()->where("department_id", 1)->pluck("name", "id");
            $data = $dep->positions;
            $users = [];
            foreach ($data as $dat) {
                foreach ($dat->getEmployees as $emp) {
                    $users["{$emp->user->id}"] = $emp->user->id;
                }
            }

            foreach ($users as $userID) {
                if ($userID == 6) { // RESTRICCION PARA ENVIAR EL CORREO SOLO A DENISSE//
                    $RH = User::where('id', $userID)->first()->name;
                    $RecursosHumanos = User::where('id', $userID)->first();
                    $RecursosHumanos->notify(new notificacionRHEdit($RH, $name, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, $diaFin, $LFin, 
                                                                    $HoraFin, $request->chair_loan, $request->description));
                    break; // Terminar el bucle después de enviar el correo al ID 6
                }
            }
            //AQUÍ SE PUEDE AGREGAR EL CORREO DE ALGÚN OTRO COLABORADOR QUE NO SEA DE RH//
            $AD= User::where('id', 147)->first()->name;
            $ADMINISTRACION = User::where('id', 147)->first();
            $ADMINISTRACION->notify(new notificacionRHEdit($AD, $name, $names, $ubica, $diaInicio, $LInicio, $HoraInicio, $diaFin, $LFin, 
                                                           $HoraFin, $request->chair_loan, $request->description));
        }

        //CORREO PARA EL DEPARTAMENTO DE SISTEMAS PARA MATERIAL (PROYECTORES)//
        if ($request->proyector > 0) {
            $SISTEMAS =User::where('id', 127)->first()->name;
            $DS =User::where('id', 127)->first();
            $DS->notify(new  notificacionSistemasEdit ($SISTEMAS, $name, $names, $ubica,$diaInicio,$LInicio,$HoraInicio, $diaFin, $LFin, 
                                                       $HoraFin, $request->proyector, $request->description));
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
