<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Obtener los datos del formulario de mensajes
        request()->validate([
            'message' => ['required', 'string'],
            'receiver_id' => ['required']

        ]);

        $transmitter_id = auth()->user()->id;
        $receiver_id = request()->receiver_id;


        //Crear el mensaje y guardarlo en la base de datos
        $message = Message::create([
            "transmitter_id" => $transmitter_id,
            "receiver_id" => $receiver_id,
            "message" => $request->message
        ]);
    }
    public function obtenerUsuarios()
    {
        $users =  User::all();
        return response()->json($users);
    }
}
