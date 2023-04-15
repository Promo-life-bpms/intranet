<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /////////////////////////////////////////////////////Mostrar vista//////////////////////////////////////////////
    public function index()
    {
        $user = auth()->user();
        $salas=boardroom::all();
        $eventos = Reservation::all();

        return view('admin.room.index', compact('salas', 'user', 'eventos'));
    }

    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request) 
    {
        $user = auth()->user();
        $request->validate([
            'title'=>'required',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required',
            'number_of_people'=>'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);

        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format("d,m,Y");
        $start = $carbon->now();
        $start = $start->format("H:i");
        $end = $carbon->now();
        $end = $end->format("H:i");

        $evento = new Reservation();
        $evento->title=$request->title;
        $evento->date=$request->date;
        $evento->start = $request->start;
        $evento->end= $request->end;
        $evento->number_of_people=$request->number_of_people;
        $evento->material=$request->material;
        $evento->chair_loan=$request->chair_loan;
        $evento->description=$request->description;
        $evento->id_usuario=$user->id;
        $evento->id_sala=$request->id_sala;
        $evento->save();
        return redirect()->back()->with('message', 'Evento creado');
    }

    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Reservation $reservation)
    {
        $this->validate(request(), [
        'date' => 'required',
        'star_time' => 'required',
        'end_time' => 'required',
        'material' => 'required',
        'chair_loan' => 'required',
        'description' => 'required',
    ]);

    // Actualizar los datos
    $reservation->date = request('date');
    $reservation->start_time = request('start_time');
    $reservation->end_time = request('end_time');
    $reservation->material = request('material');
    $reservation->chair_loan = request('chair_loan');
    $reservation->description = request('description');
    $reservation->save();

    return redirect()->route([ReservationController::class, 'index'])->with('success', 'Evento actualizado exitosamente!');
    } 

    //////////////////////////////////////////////Metodo eliminar///////////////////////////////////////////////////
    public function destroy(Reservation $reservation)
    {
    
    $reservation->delete();
    return redirect()->route([ReservationController::class, 'index'])->with('success', 'Evento eliminado exitosamente!');
    }

    /////////////////////////////////////////////Mostrar eventos////////////////////////////////////////////////////
    public function view(){
        $reserva = Reservation::all();
        $eventosFormateados = [];
        foreach ($reserva as $reservaciones) {
            $eventosFormateados[] = [
                'title' => $reservaciones->title,
                'start'=>$reservaciones->fecha." ".$reservaciones->start,
                'end'=>$reservaciones->fecha." ".$reservaciones->end,
            ];
        }
dd($eventosFormateados);
        return response()->json($eventosFormateados);
    }
}
