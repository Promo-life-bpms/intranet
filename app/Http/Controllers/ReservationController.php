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
            return redirect()->back()->with('message', "Reservación creada correctamente.");
        }
    
    //////////////////////////////////////////////Función para editar/////////////////////////////////////////////////
    public function update(Request $request)
    {
        return($request);
        $request->validate([
            'title'=>'required',
            'start' => 'required',
            'end' => 'required',
            'number_of_people'=>'required',
            'material' => 'required',
            'chair_loan' => 'required',
            'description' => 'required',
        ]);
        /*$fecha  = date('d-m-Y H:i:s');
        dd($fecha);*/
        
        $eventos=Reservation::where('id_sala', $request->id_sala)->get();

       // $start=  date("d-m-Y H:i:s", strtotime($request->start));
        
      ///  $end=  date("d-m-Y H:i:s", strtotime($request->end));
        $date=  date("d-m-Y", strtotime($request->end));
        //dd($end);


        
         
        foreach($eventos as $evento){
            $date_evento = date("d-m-Y", strtotime($evento->start));
            //$end_evento=date("d-m-Y", strtotime($evento->end));
            if($date_evento == $date){

                //dd($request->end > $evento->end);

            

            if($request->start >= $evento->start || $request->end<= $evento->end){
                return redirect()->back()->with('message2', 'ERROR'); 
                
            }

                
            if($request->start < $evento->start && $request->end <= $evento->start){

                DB::table('reservations')->where('id', $request->id_evento)->update(['title'=>$request->title,'start'=>$request->start,
                'end'=>$request->end,'number_of_people'=>$request->number_of_people,'material'=>$request->material, 
                'chair_loan' =>$request->chair_loan, 'description' =>$request->description,'id_sala' =>$request->id_sala]);
            
            return redirect()->back()->with('message2', 'Evento editado correctamente.');

                
            }
            elseif($request->start >= $evento->end && $request->end > $evento->end){

                DB::table('reservations')->where('id', $request->id_evento)->update(['title'=>$request->title,'start'=>$request->start,
                'end'=>$request->end,'number_of_people'=>$request->number_of_people,'material'=>$request->material, 
                'chair_loan' =>$request->chair_loan, 'description' =>$request->description,'id_sala' =>$request->id_sala]);
            
                return redirect()->back()->with('message1', 'Evento editado correctamente.');

                

            }

            else{
                return redirect()->back()->with('message2', 'ERROR'); 
            }
        }
            //return redirect()->back()->with('message2',$evento->end.' | '.$start);
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
