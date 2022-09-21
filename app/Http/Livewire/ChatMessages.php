<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatMessages extends Component
{
    public $idUser;
    public $ChatCollapse = true;
    public $lastMessage;

    public $mensajesEnviados = [];

    public function getListeners()
    {
        return [
            'echo:chat,MessageSent' => 'updateMessage',
        ];
    }

    public function render()
    {
        $mensajes = DB::table('messages')
            ->where('transmitter_id', auth()->user()->id)
            ->where('receiver_id', $this->idUser);

        $this->mensajesEnviados = DB::table('messages')
            ->where('receiver_id', auth()->user()->id)
            ->where('transmitter_id', $this->idUser)->union($mensajes)->orderBy('created_at', 'asc')->get();

        $user = User::find($this->idUser);
        return view('livewire.chat-messages', compact('user'));

    }

    public function collapseChat()
    {
        $this->ChatCollapse = !$this->ChatCollapse;

    }

    public function cerrarChat($idUser)
    {
        $this->emit('closeEvent', $idUser);

        /*  dd('close'); */
    }
    //obtener mensajes
    public function fetchMessages($message)
    {
        $message;
    }
    public function updateMessage($message)
    {

        $mensajes = DB::table('messages')
            ->where('transmitter_id', auth()->user()->id)
            ->where('receiver_id', $this->idUser);

        $this->mensajesEnviados = DB::table('messages')
            ->where('receiver_id', auth()->user()->id)
            ->where('transmitter_id', $this->idUser)->union($mensajes)->orderBy('created_at', 'asc')->get();

        $this->dispatchBrowserEvent('messageNew', ["id" => $this->idUser, "receiver_id" => $message['receptor']]);
    }
    /* public function messageScroll()
    {
        $this->dispatchBrowserEvent('messageNew');
    } */
}
