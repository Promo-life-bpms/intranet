<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;
use Cache;

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
        $transmitter_name = auth()->user()->name . ' ' . auth()->user()->lastname;
        $receiver_id = request()->receiver_id;
        $message = request()->message;
        $userReceiver = User::find($receiver_id);





        //Crear el mensaje y guardarlo en la base de datos
        //return response()->json([$message, $receiver_id, $transmitter_id]);


        $message = Message::create([
            "transmitter_id" => $transmitter_id,
            "receiver_id" => $receiver_id,
            "message" => $message
        ]);

        /*  broadcast(new MessageSent($transmitter_id, $message))->toOthers(); */
        event(new MessageSent($message->message, $receiver_id, $transmitter_id, $transmitter_name, $message->created_at));
        $userReceiver->notify(new MessageNotification($transmitter_id,  $message->message, $transmitter_name));
        return ['status' => 'Message Sent!', 'message' => $message];
    }

    //obtener usuarios
    public function obtenerUsuarios()
    {
        $users =  User::all();
        $newUsers = [];

        foreach ($users as $user) {
            $userOnline = false;


            if (Cache::has('user-is-online-' . $user->id)) {
                $userOnline = true;
            }
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'image' => $user->image,
                'email' => $user->email,
                'password' => $user->password,
                'userOnline' => $userOnline,
            ];
            array_push($newUsers, $data);
        }
        //dd($newUsers);
        return response()->json((object) $newUsers);
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
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return back();
    }
    public function Notificaciones()
    {

        $notificationUnread = auth()->user()->unreadNotifications;
        $countNotifications = count($notificationUnread);


        return response()->json(['countNotifications' => $countNotifications, 'notificationUnread' => $notificationUnread]);
    }
}
