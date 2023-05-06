<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;
use  App\Models\Soporte\Ticket;
use App\Models\Soporte\Categoria;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\Historial;
use App\Notifications\SoporteNotification;
use App\Notifications\StatuSoporteFinalizadoNotification;

class ListadoTicketsComponent extends Component
{

    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ticket_id, $name, $categoria, $data, $categorias, $actualizar_status, $ticket_solucion, $mensaje;

    public function render()
    {

        $this->categorias = Categoria::where('status', true)->get();
        return view('livewire.listado-tickets-component', [

            'tickets' => Ticket::where('user_id', auth()->id())->orderBy('id')->paginate(15)

        ]);
    }



    public function guardar()
    {
        if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('data', 'La descripcion es obligatoria');
            return;
        }


        $this->validate(
            [
                'name' => 'required',
                'data' => 'required',
                'categoria' => 'required'
            ]
        );

        //encuentra la categoria del ticket
        $category = Categoria::find((int) $this->categoria);
        //encuentrar a los usuarios relacionados con la categoria del ticket
        $usuarios =  $category->usuarios;


        // dd($usuarios);

        //  $ticket_usuario = Ticket::whereIn('user_id',$category);

        // $tickets = $category->tickets()->whereIn('user_id', $category->usuarios->pluck('id'))->get();


        foreach ($usuarios as $usuario) {
            //aqui contamos los tickets del usuario
            $usuario->ticketsusuario->count();

            dd( $usuario->ticketsusuario->count());

        }







        $ticket = Ticket::create(
            [
                'name' => $this->name,
                'data' => $this->data,
                'category_id' => (int) $this->categoria,
                'user_id' => auth()->user()->id,
                'status_id' => 1
            ]
        );



        //Historial de creado
        Historial::create(
            [
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id,
                'type' => 'creado',
                'data' => $ticket->data

            ]
        );


        $Notificacion =
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'name_ticket' => $ticket->name,
                'data' => $ticket->data,
                'tiempo' => $ticket->created_at
            ];

        $usuarios->notify(new SoporteNotification($Notificacion));

        $this->name = '';
        $this->categoria = '';
        $this->dispatchBrowserEvent('ticket_success');
    }


    public function editarTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->id;
        $this->dispatchBrowserEvent('mostrar_data');
    }

    public function guardarEditar($id)
    {
        $ticketEditar = Ticket::find($id);
        if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('data', 'La descripcion es obligatoria');
            return;
        }

        $this->validate(
            [
                'name' => 'required',
                'data' => 'required',
                'categoria' => 'required'
            ]

        );

        $ticketEditar->update([
            'name' => $this->name,
            'data' => $this->data,
            'category_id' => $this->categoria,
        ]);

        Historial::create([
            'ticket_id' => $ticketEditar->id,
            'user_id' => auth()->user()->id,
            'type' => 'edito',
            'data' => $ticketEditar->data
        ]);

        $this->name = '';
        $this->categoria = ' ';
        $this->dispatchBrowserEvent('editar');
    }

    public function finalizarTicket($id)
    {
        $actualizar_status = Ticket::find($id);

        $actualizar_status->update(
            [

                'status_id' => 3
            ]
        );



        Historial::create(

            [
                'ticket_id' => $actualizar_status->id,
                'user_id' => auth()->user()->id,
                'type' => 'status',
                'data' => $actualizar_status->status->name
            ]
        );


        $NotificacionStatus =
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'name_ticket' => $actualizar_status->name,
                'status' => $actualizar_status->status->name

            ];

        // auth()->user()->notify(new StatuSoporteFinalizadoNotification($NotificacionStatus));
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->ticket_solucion = $ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
    }

    public function enviarMensaje()
    {

        Mensaje::create([
            'ticket_id' => $this->ticket_id,
            'message' => $this->mensaje,
            'user_id' => auth()->user()->id
        ]);


        Historial::create(

            [
                'ticket_id' => $this->ticket_id,
                'user_id' => auth()->user()->id,
                'type' => 'Mensaje',
                'data' => $this->mensaje
            ]
        );

        $this->dispatchBrowserEvent('Mensaje');
    }
}
