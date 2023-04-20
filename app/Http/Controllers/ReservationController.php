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
        $salas=boardroom::all();
        $eventos = Reservation::all();
        return view('admin.room.index', compact('salas', 'user', 'eventos'));
    }

    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request) 
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $id_sala = $request->input('id_sala');
        
    
        $evento_existente = Reservation::where('start', $start)->where('id_sala', $id_sala)->first();
        if ($evento_existente) {
            return redirect()->back()->withErrors(['fecha_hora' => 'Ya existe un evento en esta fecha y hora']);
        }else{
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
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Request $request)
    {

        /*$eventos=Reservation::all()->pluck('id');
        dd($eventos);
        if ($request->eventos == auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este evento');
        
        }else{
           */
            //$prueba=Reservation::all()->where('id', $request->id_evento);
            //dd($prueba);
            $request->validate([
                'title'=>'required',
                'start' => 'required',
                'end' => 'required',
                'number_of_people'=>'required',
                'material' => 'required',
                'chair_loan' => 'required',
                'description' => 'required',
                'id_sala'=>'required'
            ]);
            
            $carbon = new \Carbon\Carbon();
            $start = $carbon->now();
            $start = $start->format("d-m-Y\TH:i");
            $end = $carbon->now();
            $end = $end->format("d-m-Y\TH:i");

            

            
            DB::table('reservations')->where('id', $request->id_evento)->update(['title'=>$request->title,'start'=>$request->start,
            'end'=>$request->end,'number_of_people'=>$request->number_of_people,'material'=>$request->material, 
            'chair_loan' =>$request->chair_loan, 'description' =>$request->description,'id_sala' =>$request->id_sala]);

            return redirect()->back()->with('message', 'Evento editado correctamente');
        }
    //} 

    //////////////////////////////////////////////Metodo eliminar///////////////////////////////////////////////////
    public function destroy(Request $request)
    {

        DB::table('reservations')->where('id', $request->id_evento)->delete();
        return redirect()->back()->with('message', 'Evento eliminado');  
    }
    /////////////////////////////////////////////Mostrar eventos////////////////////////////////////////////////////
    public function view(Reservation $reservation){
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
   
}
