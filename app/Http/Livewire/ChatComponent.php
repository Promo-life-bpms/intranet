<?php

namespace App\Http\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Queue\Listener;
use Illuminate\Validation\Rules\Exists;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;
use Illuminate\Support\Facades\Cache;

class ChatComponent extends Component
{

    public $search = '';
    public $listaChatsAbiertos = [];
    public $listUsersCollapse = false;
    protected $listeners = ['closeEvent' => 'cerrarChat'];

    public function render()
    {
        $search = '%' . $this->search . '%';
        $users   = User::where('name', 'LIKE', $search)->where('status', 1)->orderBy("name", "ASC")->get();
        $usersOnline = [];

        foreach ($users as $user) {
            $userOnline = false;

            if (Cache::has('user-is-online-' . $user->id)) {
                $userOnline = true;
            }
            $user->isOnline = $userOnline;

            array_push($usersOnline, $user);
        }
        return view('livewire.chat-component', ['users' => $usersOnline]);
    }

    public function openChat($id)
    {
        if (count($this->listaChatsAbiertos) < 2) {
            if (in_array($id, $this->listaChatsAbiertos) == false) {
                array_push($this->listaChatsAbiertos, $id);
            }
        }
        $this->dispatchBrowserEvent('chatStorage', ['chat' => $this->listaChatsAbiertos]);
    }
    public function collapseListUsers()
    {
        $this->listUsersCollapse =  !$this->listUsersCollapse;
    }

    public function cerrarChat($id)
    {
        unset($this->listaChatsAbiertos[array_search($id, $this->listaChatsAbiertos)]);

        $this->dispatchBrowserEvent('chatStorage', ['chat' => $this->listaChatsAbiertos]);
    }
}
