<?php

namespace App\Http\Controllers;

use App\Models\boardroom;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class BoardroomController extends Controller
{
    //////////////////////////////////////////////////// VISTA /////////////////////////////////////////////////////////
    public function vista()
    {
        $user = auth()->user();
        $salas=boardroom::all();
        $eventos = Reservation::all();
        
        return view('admin.room.dispo', compact('salas', 'user', 'eventos'));
    }
    ////////////////////////////////////////////TRAER INFORMACÓN DE LAS LLAVES FORANEAS //////////////////////////////

    public function mostrarNombre()
    {
        $usuarios = Reservation::with('users')->get();
        $salas=Reservation::with('boordroms')->get();
        return view('admin.room.dispo', compact('usuarios','salas'));

    }
    //////////////////////////////////////////TRAER INFORMACÓN DE LOS EVENTOS ///////////////////////////////////////

    public function view(Reservation $reservation){
        $reservation = Reservation::all();
        return response()->json($reservation);
    }
}
