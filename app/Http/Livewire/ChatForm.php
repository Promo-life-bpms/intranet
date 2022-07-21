<?php

namespace App\Http\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Livewire\Component;

class ChatForm extends Component
{
    public $userId;
    public $message;

    public function render()
    {
        return view('livewire.chat-form');
    }
    public function sendMessage()
    {

        $transmitter_id = auth()->user()->id;
        $transmitter_name = auth()->user()->name . ' ' . auth()->user()->lastname;
        $receiver_id = $this->userId;
        $message = $this->message;
        $userReceiver = User::find($receiver_id);
        $image = auth()->user()->image;

        //Crear el mensaje y guardarlo en la base de datos


        $message = Message::create([
            "transmitter_id" => $transmitter_id,
            "receiver_id" => $receiver_id,
            "message" => $message
        ]);

        /*  broadcast(new MessageSent($transmitter_id, $message))->toOthers(); */
        event(new MessageSent($message->message, $receiver_id, $transmitter_id, $transmitter_name, $message->created_at));
        $userReceiver->notify(new MessageNotification($transmitter_id,  $message->message, $transmitter_name, $image));


        $this->emit('messageEvent', $message);

        $this->message = '';
    }
}
