<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    //enviar mensajes
    public function sendMessage(Request $request)
    {
        //Obtener los datos del formulario de mensajes
        /*         request()->validate([
            'message' => ['required', 'string'],
            'receiver_id' => ['required']

        ]); */

        $transmitter_id = auth()->user()->id;
        $receiver_id = request()->receiver_id;
        $message = request()->message;

        //return response()->json($message, $receiver_id, $transmitter_id);


        //Crear el mensaje y guardarlo en la base de datos
        $message = Message::create([
            "transmitter_id" => $transmitter_id,
            "receiver_id" => $receiver_id,
            "message" => $message
        ]);

        /*  broadcast(new MessageSent($transmitter_id, $message))->toOthers(); */
        event(new MessageSent($message->message, $receiver_id, $transmitter_id));
        return ['status' => 'Message Sent!'];
    }

    //obtener usuarios
    public function obtenerUsuarios()
    {
        $users =  User::all();
        return response()->json($users);
    }

    //obtener mensajes
    public function fetchMessages($userId)
    {

        $mensajes = DB::table('messages')
            ->where('transmitter_id', auth()->user()->id)
            ->where('receiver_id', $userId);

        /*  SELECT * FROM messages WHERE transmitter_id = 1 AND receiver_id = 2 UNION SELECT * FROM messages WHERE transmitter_id = 2 AND receiver_id = 1 ORDER BY created_at; */

        $mensajesEnviados = DB::table('messages')
            ->where('receiver_id', auth()->user()->id)
            ->where('transmitter_id', $userId)->union($mensajes)->orderBy('created_at', 'asc')->get();

        //$chat = $mensajes->union($mensajesEnviados)->orderBy('created_at', 'desc')->get();



        return response()->json(['mensajesEnviados' => $mensajesEnviados], 200);
    }
}
