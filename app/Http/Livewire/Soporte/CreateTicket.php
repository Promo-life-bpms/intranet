<?php

namespace App\Http\Livewire\Soporte;
use App\Models\Soporte\Ticket;
use Livewire\Component;

class CreateTicket extends Component
{

   public $name;

    public function render()
    {
        return view('livewire.soporte.create-ticket');
    }

    public function store()
    {
        dd(1);
        Ticket::create(
             [
                 'name'=>$this->name

             ]
         );
    }


    
}
