<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Soporte\Ticket;
use App\Models\Soporte\Solucion;




class SoporteSolucionComponent extends Component
{
    use WithPagination;
    public $ticket_id, $name, $categoria, $data, $categorias, $description;

    public function render()
    {


        return view('livewire.soporte-solucion-component', [
            'solucion' => Ticket::orderBy('id')->paginate('15')
        ]);
    }

    public function enProceso($id)
    {
        $actualizar_status = Ticket::find($id);
        $actualizar_status->update([
            'status_id' => 2
        ]);
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
        
    }

    public function guardarSolucion()
    {

        if ($this->description == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('description', 'La descripcion es obligatoria');
            return;
        }


        $this->validate(
            [
                'description' => 'required'
            ]
        );

        Solucion::create([
            'description' => $this->description,
            'user_id' =>auth()->user()->id,
            'ticket_id' =>$this->ticket_id
        ]);

        $this->dispatchBrowserEvent('ticket_solucion');
    }

    
}
