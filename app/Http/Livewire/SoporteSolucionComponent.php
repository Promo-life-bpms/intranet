<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Soporte\Ticket;
use App\Models\Soporte\Solucion;
use Illuminate\Support\Facades\Auth;
use App\Models\Soporte\UsuariosSoporte;





class SoporteSolucionComponent extends Component
{
    use WithPagination;
    public $ticket_id, $name, $categoria, $data, $categorias, $description,$mensaje;

    public function render()
    {

        $categories=  auth()->user()->asignacionCategoria->pluck(["id"]);

        return view('livewire.soporte-solucion-component', [
            
            'solucion' => Ticket::whereIn('category_id',$categories)->paginate('15')
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
        //dd($this->mensaje=$ticket->mensajes);
        $this->mensaje=$ticket;
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
            'user_id' => auth()->user()->id,
            'ticket_id' => $this->ticket_id
        ]);

        $this->dispatchBrowserEvent('ticket_solucion');
    }
}
