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
        $salitas=boardroom::all();
        $salas=Reservation::with('boordroms')->get();
        $boardroom=boardroom::all()->pluck('name', 'id');
        $eventos = Reservation::all();
        return view('admin.room.index', compact('salitas', 'user', 'eventos', 'boardroom'));
    }
    /////////////////////////////////////////////Función crear evento///////////////////////////////////////////////
    public function store(Request $request) 
    {
        
        $eventos=Reservation::where('id_sala', $request->id_sala)->get();
        $start = $request->start;
        $end = $request->end;

        foreach($eventos as $evento)

        if($evento->start <= $start && $evento->end >= $end){
        return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
        }
       
        elseif($end < $start){
            return back()->with('message1',"La hora de inicio debe ser previa que la hora del fin de la reservacón.");
        }
        $user = auth()->user();
        $request->validate([
            'title'=>'required',
            'start' => 'required',
            'end' => 'required|after:start',
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
            return redirect()->back()->with('message', "Reservacón creada correctamente.");
        }
    
    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'start' => 'required',
            'end' => 'required',
            'number_of_people'=>'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);

        $eventos=Reservation::where('id_sala', $request->id_sala)->get();
        //dd($eventos);
        //$personas=boardroom::where('capacitance', $request->capacitance)->get();
        $start=  date("d-m-Y H:i:s", strtotime($request->start));
        $end=  date("d-m-Y H:i:s", strtotime($request->end));

        if($end < $start){
            return back()->with('message1',"La hora de inicio debe ser previa que la hora del fin de la reservacón.");
        }
         
        foreach($eventos as $evento){
            /*if($evento -> start >= $start || $evento->end <= $end){
                return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
            }*/

          /*  if($end >= $evento->end || $start <= $evento->end){
                return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
            }*/

           /* if($start < $evento->start && $end <= $evento-> start){
                return back()->with('message1', "prueba.");

                if($end < $evento->start){
                    $carbon = new \Carbon\Carbon();
                    $start = $carbon->now();
                    $start = $start->format("d-m-Y H:i:s");
                    $end = $carbon->now();
                    $end = $end->format("d-m-Y H:i:s");
                    
                    DB::table('reservations')->where('id', $request->id_evento)->update(['title'=>$request->title,'start'=>$request->start,
                    'end'=>$request->end,'number_of_people'=>$request->number_of_people,'material'=>$request->material, 
                    'chair_loan' =>$request->chair_loan, 'description' =>$request->description,'id_sala' =>$request->id_sala]);
                
                return redirect()->back()->with('message2', 'Evento editado correctamente.');
            }
            else{
            return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
            }
           /* if($start <= $evento->end){
                return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");
            } 
               
            }
            return back()->with('message1', "Ya existe un evento en esta fecha y hora. Por favor elige otra sala u otra hora y fecha.");*/
            
            
            if($start < $evento->start || $end <= $evento->start){
                return back()->with('message1', "FILTRO DOS");
                  

            }

            if($start <= $evento->start || $end >= $evento->end){
                return back()->with('message1', "uno");

            }
           /* if($start >= $evento->end || $end <= $evento->end){
                return back()->with('message1', "FILTRO");
            }*/
            
            else{
            $carbon = new \Carbon\Carbon();
            $start = $carbon->now();
            $start = $start->format("d-m-Y H:i:s");
            $end = $carbon->now();
            $end = $end->format("d-m-Y H:i:s");
            
            DB::table('reservations')->where('id', $request->id_evento)->update(['title'=>$request->title,'start'=>$request->start,
            'end'=>$request->end,'number_of_people'=>$request->number_of_people,'material'=>$request->material, 
            'chair_loan' =>$request->chair_loan, 'description' =>$request->description,'id_sala' =>$request->id_sala]);
        
        return redirect()->back()->with('message2', 'Evento editado correctamente.');
    }
}
}
    
    
    //////////////////////////////////////////////Metodo eliminar///////////////////////////////////////////////////
    public function destroy(Request $request)
    {

        DB::table('reservations')->where('id', $request->id_evento)->delete();
        return redirect()->back()->with('message1', 'Evento eliminado.');  
    }
    /////////////////////////////////////////////Mostrar eventos////////////////////////////////////////////////////
    public function view(Reservation $reservation){
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
   
}
