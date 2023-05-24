<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Soporte\Ticket;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\Solucion;
use App\Models\Soporte\Historial;
use App\Notifications\MessageSoporteSolutionNotification;
use App\Notifications\SolucionSoporteNotification;
use App\Notifications\StatusEnProcesoSoporteNotification;

class SoporteSolucionComponent extends Component
{
    use WithPagination;
    public $ticket_id, $name, $categoria, $data, $categorias, $description, $mensaje, $status, $historial, $usuario, $mensajes;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $categories =  auth()->user()->asignacionCategoria->pluck(["id"]);
        return view('livewire.soporte-solucion-component', [

            'solucion' => Ticket::whereIn('category_id', $categories)->paginate('15')
        ]);
    }


    public function enProceso($id)
    {

        $actualizar_status = Ticket::find($id);
        $user_ticktet = Ticket::find($id);
        $user = $user_ticktet->user;

        $actualizar_status->update([
            'status_id' => 2
        ]);

        Historial::create([
            'ticket_id' => $actualizar_status->id,
            'user_id' => auth()->user()->id,
            'type' => 'status',
            'data' => $actualizar_status->status->name
        ]);

        $notificacionEnProceso = [
            'name' => auth()->user()->name,
            'name_ticket' => $actualizar_status->name,
            'status' => $actualizar_status->status->name
        ];

        $user->notify(new StatusEnProcesoSoporteNotification($notificacionEnProceso));
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $message = Mensaje::find($this->ticket_id);

        $this->usuario = $message;
        $this->status = $ticket;
        $this->historial = $ticket;
        $this->mensaje = $ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
        $this->dispatchBrowserEvent('cargar');
    }

    public function guardarSolucion()
    {

        $ticket = Ticket::find($this->ticket_id);
        $usuario = $ticket->user;
        if ($this->description == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('description', 'La descripcion es obligatoria');
            return;
        }
        $this->validate(
            [
                'description' => 'required'
            ]
        );

        $ticket->update([
            'status_id' => 3
        ]);


        Solucion::create([
            'description' => $this->description,
            'user_id' => auth()->user()->id,
            'ticket_id' => $this->ticket_id
        ]);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => auth()->user()->id,
            'type' => 'solucion',
            'data' => $this->description
        ]);

        $solucionNotification = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticket->name,
        ];

        $usuario->notify(new SolucionSoporteNotification($solucionNotification));
        $this->dispatchBrowserEvent('ticket_solucion');
    }

    //enviar mensaje en soporte solucion
    public function mensaje()
    {

        $ticket = Ticket::find($this->ticket_id);
        $usuario = $ticket->user;

        Mensaje::create([
            'ticket_id' => $this->ticket_id,
            'message' => $this->mensajes,
            'user_id' => auth()->user()->id
        ]);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => auth()->user()->id,
            'type' => 'Mensaje',
            'data' => $this->mensajes
        ]);

        $messageNotification = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticket->name,
        ];

        $usuario->notify(new MessageSoporteSolutionNotification($messageNotification));
        $this->dispatchBrowserEvent('message');
    }
}
