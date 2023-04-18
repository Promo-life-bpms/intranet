<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Facade\FlareClient\Stacktrace\File;
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
            'start' => 'required',
            'end' => 'required',
            'number_of_people'=>'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);
      
            $carbon = new \Carbon\Carbon();
            $start = $carbon->now();
            $start = $start->format("d-m-Y\TH:i");
            $end = $carbon->now();
            $end = $end->format("d-m-Y\TH:i");
            $evento = new Reservation();
            $evento->title=$request->title;
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

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function edit($id)
    {
        // Obtén el evento a editar
        $eventos = Reservation::find($id);
        // Retorna la vista del formulario de edición con los datos del evento
        return view('admin.room.index',compact('id', 'eventos'));
    }       
    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
        'start' => 'required',
        'end' => 'required',
        'material' => 'required',
        'chair_loan' => 'required',
        'description' => 'required',
        'id'=>'required'
    ]);
    
    $carbon = new \Carbon\Carbon();
    $start = $carbon->now();
    $start = $start->format("d-m-Y\TH:i");
    $end = $carbon->now();
    $end = $end->format("d-m-Y\TH:i");

    $reservation =Reservation::find($id);
    $reservation->title=$request->title;
    $reservation->start = $request->start;
    $reservation->end= $request->end;
    $reservation->number_of_people=$request->number_of_people;
    $reservation->material=$request->material;
    $reservation->chair_loan=$request->chair_loan;
    $reservation->description=$request->description;
    $reservation->id_usuario=$user->id;
    $reservation->id_sala=$request->id_sala;
    $reservation->save();
    return redirect()->back()->with('message', 'Evento editado correctamente');
    } 

    //////////////////////////////////////////////Metodo eliminar///////////////////////////////////////////////////
    public function destroy(Reservation $reservation)
    {
    
    $reservation->delete();
    return redirect()->route([ReservationController::class, 'index'])->with('success', 'Evento eliminado exitosamente!');
    }

    /////////////////////////////////////////////Mostrar eventos////////////////////////////////////////////////////
    public function view(Reservation $reservation){
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
   
}
