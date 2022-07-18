<?php

namespace App\Http\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Filesystem\Cache;
use Illuminate\Queue\Listener;
use Illuminate\Validation\Rules\Exists;
use Livewire\Component;

class ChatComponent extends Component
{


    public $listaChatsAbiertos = [];
    public $listUsersCollapse = false;


    public function render()
    {
        $users   = User::where('status', 1)->orderBy("name", "ASC")->get();
        return view('livewire.chat-component', compact('users'));
    }

    public function openChat($id)
    {
        if (count($this->listaChatsAbiertos) < 3) {
            if (in_array($id, $this->listaChatsAbiertos) == false) {
                array_push($this->listaChatsAbiertos, $id);
            }
        }
    }
    public function collapseListUsers()
    {
        $this->listUsersCollapse =  !$this->listUsersCollapse;
    }
    /* public function getMessage()
    {
        return [
            'echo:chat,MessageSent' => 'updateMessage',
        ];
    } */
    /*     public function obetenerUsuarios()
    {

        if (Cache::has('user-is-online-' . $user->id)) {
            $userOnline = true;
        }


        //dd($newUsers);
    } */

    /*   public function fetchMessages($userId)
    {

        $mensajes = DB::table('messages')
            ->where('transmitter_id', auth()->user()->id)
            ->where('receiver_id', $userId);

        $mensajesEnviados = DB::table('messages')
            ->where('receiver_id', auth()->user()->id)
            ->where('transmitter_id', $userId)->union($mensajes)->orderBy('created_at', 'asc')->get();

        $chat = $mensajes->union($mensajesEnviados)->orderBy('created_at', 'desc')->get();

         return response()->json(['mensajesEnviados' => $mensajesEnviados], 200);
    } */
}
