<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        /*$eventos = Reservation::where('id_sala', $request->id_sala)->get();
        $start = $request->start;
        $end = $request->end;*/

        /*foreach ($eventos as $evento)

            if ($evento->start <= $start && $evento->end >= $end) {
                return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
            } elseif ($end < $start) {
                return back()->with('message1', "La hora de inicio debe ser previa que la hora del fin de la reservacón.");
            }*/
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

        $start =  date("d-m-Y H:i:s", strtotime($request->start));
        $end =  date("d-m-Y H:i:s", strtotime($request->end));
        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;
        
        $eventos = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
            //return($eventos);
        
        
        $fecha_actual = Carbon::now()->format('d/m/Y H:i:s');
        //dd($fecha_actual);

        $inicio = Reservation::where('start', '<=', $fecha_termino)
            ->where('start', '>=',  $fecha_inicio)
            ->where('id_sala', $request->id_sala)
            ->get();
            //return($inicio);
        $DELANTE = Reservation::where('end', '>=', $fecha_inicio)
            ->where('end', '>', $fecha_inicio)
            ->where('id_sala', $request->id_sala)
            ->get();


        //dd($inicio->toArray(), $DELANTE->toArray());
        
            if ($end < $start) {
                return back()->with('message1', "La hora de inicio debe ser previa que la hora del fin de la reservacón.");
            }
            if($eventos != $fecha_actual){
                if (!$inicio->count() == 0 || !$DELANTE->count() == 0) {
                $carbon = new \Carbon\Carbon();
                $start = $carbon->now();
                $start = $start->format("Y-m-d H:i:s");
                $end = $carbon->now();
                $end = $end->format("Y-m-d H:i:s");
                $evento = new Reservation();
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
                return redirect()->back()->with('message', "Reservación creada correctamente.");
            }else {
                return back()->with('message1', "Error.");
            }
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

        $start =  date("d-m-Y H:i:s", strtotime($request->start));
        $end =  date("d-m-Y H:i:s", strtotime($request->end));

        //como traer eventos de un afecha a una fecha///
        //separar
        $fecha_inicio =  $request->start;
        $fecha_termino =  $request->end;
        $eventos = Reservation::whereDate('start', Carbon::parse($fecha_inicio)->format('Y-m-d'))
            ->whereDate('end', Carbon::parse($fecha_termino)->format('Y-m-d'))
            ->where('id_sala', $request->id_sala)->get();
            return($eventos);

        $fin = Reservation::where('start', '<=', $request->end)
            ->where('start', '<',  $request->start)
            ->where('id_sala', $request->id_sala)
            ->get();
            //dd($fin);

        $DELANTE = Reservation::where('end', '>=', $request->start)
            ->where('id_sala', $request->id_sala)
            ->get();

        //dd(count($fin) . "  |  " . count($DELANTE));


        if ($fin->count() == 1) {

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
