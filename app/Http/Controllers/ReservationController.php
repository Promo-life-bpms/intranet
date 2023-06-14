<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Position;
use App\Models\Postulant;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReservationController extends Controller
{
    /////////////////////////////////////////////////////Mostrar vista//////////////////////////////////////////////
    public function index(Request $request)
    {
        $user = auth()->user();
        $salitas = boardroom::all();
        $personas= User::all();
        $boardroom = boardroom::all()->pluck('name', 'id');
        //$usuarios = User::all('id')->where('id', auth()->user()->id);
        //dd($usuarios)
        $eventos = Reservation::all();
        $departments  = Department::pluck('name', 'id')->toArray();
        return view('admin.room.index', compact('user','salitas','personas','boardroom','eventos','departments'));
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
    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request)
    {
       // dd($request);
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
    
        //OBTENEMOS EL ARREGLO QUE DE LA VISTA (ARREGLO DEL MATERIAL)//
        $seleccionarMaterial= $request->material;
        //CONVERTIMOS EL ARREGLO EN STRING PARA PODER GUARDAR LA INFORMACIÓN//
        $mate= implode(','.' ',$seleccionarMaterial);

        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $ejemplo=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($ejemplo,$usuario->id);
                ///Enviar correos que tengan este id///
            }   
        }
        
        //UNA VEZ QUE YA PASO LAS VALIDACIÓNES CREA EL EVENETO//
        $evento = new Reservation();
        $evento->title = $request->title;
        $evento->start = $request->start;
        $evento->end = $request->end;
        $evento->guest = json_encode($ejemplo);
        $evento->material = $mate;
        $evento->chair_loan= $request->chair_loan;
        $evento->description = $request->description;
        $evento->id_usuario = $user->id;
        $evento->id_sala = $request->id_sala;
        $evento->save();
        return redirect()->back()->with('message', "Reservación creada correctamente.");
    }
          
    
    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
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

        //OBTENEMOS EL ARREGLO QUE DE LA VISTA (ARREGLO DEL MATERIAL)//
        $seleccionarMaterial= $request->material;
        //CONVERTIMOS EL ARREGLO EN STRING PARA PODER GUARDAR LA INFORMACIÓN//
        $mate= implode(','.' ',$seleccionarMaterial);

        //CREAMOS UN ARREGLO PARA OBTENER LOS DATOS NECESARIOS DEL GUEST//
        $ejemplo=[];
        $usuarios=User::all();
        foreach($usuarios as $usuario){
            if($request->has('guest'.strval($usuario->id))){
                array_push($ejemplo,$usuario->id);
                ///Enviar correos que tengan este id///
            }   
        }

        DB::table('reservations')->where('id', $request->id_evento)->update([
            'title' => $request->title, 'start' => $request->start,
            'end' => $request->end, 'guest' => json_encode($ejemplo), 'material' => $mate,
            'chair_loan' => $request->chair_loan, 'description' => $request->description, 'id_sala' => $request->id_sala
            ]);
            return redirect()->back()->with('message2', "Evento editado correctamente.");
    }

    //////////////////////////////////////////////Metodo eliminar///////////////////////////////////////////////////
    public function destroy(Request $request)
    {

        DB::table('reservations')->where('id', $request->id_evento)->delete();
        return redirect()->back()->with('message1', 'Evento eliminado.');
    }
    /////////////////////////////////////////////Mostrar eventos////////////////////////////////////////////////////
    public function view(Reservation $reservation)
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
}