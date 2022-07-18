<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ChatMessages extends Component
{
    public $idUser;
    public $ChatCollapse = true;

    public function render()
    {
        $user = User::find($this->idUser);
        return view('livewire.chat-messages', ['user' => $user]);
    }
    public function collapseChat()
    {

        $this->ChatCollapse = !$this->ChatCollapse;
    }
    public function closeChat()
    {
        dd('close');
    }
}
