<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class ReservationController extends Controller
{
    /////////////////////////////////////////////////////Mostrar vista//////////////////////////////////////////////
    public function index()
    {
        $user = auth()->user();
        $salitas = boardroom::all();
        $salas = Reservation::with('boordroms')->get();
        $boardroom = boardroom::all()->pluck('name', 'id');
        $eventos = Reservation::all();
        return view('admin.room.index', compact('salitas', 'user', 'eventos', 'boardroom'));
    }
    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required|after:start',
            'number_of_people' => 'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);

        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;

        $EventosDelDia = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
        //return ($EventosDelDia);

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
        $inicio = $request->start; // Fecha de inicio del form
        $fechastart = Carbon::parse($inicio);
        $fechaInicio = strtotime($fechastart->format('Y-m-d H:i:s')) * 1000;

        $final = $request->end; //fecha de fin del form
        $fechaend = Carbon::parse($final);
        $fechaFinal = strtotime($fechaend->format('Y-m-d H:i:s')) * 1000;

        foreach ($eventosRefactorizados as $evento) {

            if ($fechaInicio >= $evento['start'] && $fechaInicio <= $evento['end']) {
                // Si esta dentro del el rango
                return redirect()->back()->with('message1', "Si esta dentro del rango 1.");

            }
            if($fechaFinal >= $evento['start']){
                return redirect()->back()->with('message1', "Si esta dentro del rango 2.");

            }else{
                // NO esta, avanza a la siguiente validacion
            }


            //dd($fechaInicio . " | " . $fechaFinal);
            //dd($nuevo['start'], $fechaInicio, $nuevo['end'], $fechaFinal);

            //if ($nuevo['start'] <= $fechaInicio || $nuevo['start'] < $fechaFinal) {
                //dd('14');
                /*$evento = new Reservation();
                $evento->title = $request->title;
                $evento->start = $request->start;
                $evento->end = $request->end;
                $evento->number_of_people = $request->number_of_people;
                $evento->material = $request->material;
                $evento->chair_loan = $request->chair_loan;
                $evento->description = $request->description;
                $evento->id_usuario = $user->id;
                $evento->id_sala = $request->id_sala;
                $evento->save();
                return redirect()->back()->with('message', "Reservación creada correctamente.");*/
           // } /*else {
                //return back()->with('message1', "Error.");
            //}*/
        }
    }
          
    
    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'number_of_people' => 'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);
        $inicio = strtotime($request->start) * 1000;

        $fin =  strtotime($request->end) * 1000;
        //dd($inicio." | ".$fin);

        //como traer eventos de un afecha a una fecha///
        //separar
        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;
        $eventos = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
        //dd($start, $end, $eventos->toArray());

        $filtro = [];
        foreach ($eventos as $item) {

            $componentes = [
                'id' => $item['id'],
                'start' => strtotime($item['start']) * 1000,
                'end' => strtotime($item['end']) * 1000,
                'id_sala' => $item['id_sala']
            ];
            $filtro[] = $componentes;
        }
        dd($filtro);

        $ATRAS = Reservation::where('start', '<=', $request->end)
            ->where('start', '<',  $request->start)
            ->where('id_sala', $request->id_sala)
            ->get();
        //dd($fin);

        $DELANTE = Reservation::where('end', '>=', $request->start)
            ->where('id_sala', $request->id_sala)
            ->get();

        //dd($ATRAS->toArray(), $DELANTE->toArray());


        if (!$ATRAS->count() == 0) {

            DB::table('reservations')->where('id', $request->id_evento)->update([
                'title' => $request->title, 'start' => $request->start,
                'end' => $request->end, 'number_of_people' => $request->number_of_people, 'material' => $request->material,
                'chair_loan' => $request->chair_loan, 'description' => $request->description, 'id_sala' => $request->id_sala
            ]);
            return back()->with('message2', "Evento editado correctamente.");
        } else {
            return back()->with('message1', "Error.");
        }
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
