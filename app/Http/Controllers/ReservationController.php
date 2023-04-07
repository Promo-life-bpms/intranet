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
        $salas=boardroom::all();
        $events = Reservation::all();
        $usuarios= User::all();
        return view('admin.room.index', compact('events', 'salas', 'usuarios'));
    }

    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request) 
    {
        dd($request);
        $request->validate([
            'date' => 'required',
            'star_time' => 'required',
            'end_time' => 'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);
 
        
        $user = auth()->user();
        $name = auth()->name();
        $evento = new Reservation();
        $evento->date=$request->input('date');
        $evento->star_time = $request->input('star_time');
        $evento->end_time= $request->input('end_time');
        $evento->material=$request->input('material');
        $evento->chair_loan=$request->input('chair_loan');
        $evento->description=$request->input('description');
        $evento->id_usuario= $user->users->id;
        $evento->id_sala= $name->boardrooms->name;
        $evento->save();
        return redirect()->route([ReservationController::class, 'index'])->with('success', 'Evento creado');
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
}
