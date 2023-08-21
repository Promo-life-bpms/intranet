<?php

namespace App\Http\Livewire;

use App\Http\Controllers\FirebaseNotificationController;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;
use  App\Models\Soporte\Ticket;
use App\Models\Soporte\Categoria;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\encuesta;
use App\Models\Soporte\Historial;
use App\Notifications\EditarTicketNotification;
use App\Notifications\SoporteNotification;
use App\Notifications\StatuSoporteFinalizadoNotification;
use App\Notifications\MessageSoporteNotification;

class ListadoTicketsComponent extends Component
{

    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ticket_id, $name, $categoria, $data, $categorias, $actualizar_status, $ticket_solucion, $mensaje, $mensajes, $user, $score, $comments, $prioridad, $estrellas,
    $especial;

    public function render()
    {

        $this->categorias = Categoria::where('status', true)->get();
        return view('livewire.listado-tickets-component', [

            'tickets' => Ticket::where('user_id', auth()->id())->orderBy('id')->paginate(15)
        ]);
    }

    public function guardar()
    {

        $this->validate(
            [
                'name' => 'required',
                'data' => 'required|max:10000000',
                'categoria' => 'required'
            ]
        );

        $category = Categoria::find((int) $this->categoria);
        $usuarios = $category->usuarios;
        $cantidadTicketsMenor = null;
        $usuariosConMenosTickets = [];

        // Encontrar usuarios con menos tickets
        foreach ($usuarios as $usuario) {
            $cantidadTickets = $usuario->tickets->count();

            if ($cantidadTicketsMenor === null || $cantidadTickets < $cantidadTicketsMenor) {
                $cantidadTicketsMenor = $cantidadTickets;
                $usuariosConMenosTickets = [$usuario];
            } elseif ($cantidadTickets === $cantidadTicketsMenor) {
                $usuariosConMenosTickets[] = $usuario;
            }
        }

        if (count($usuariosConMenosTickets) > 0) {
            // Elegir un usuario al azar entre aquellos con menos tickets
            $usuarioConMenosTickets = $usuariosConMenosTickets[array_rand($usuariosConMenosTickets)];
        } else {
            // Elegir un usuario al azar si no se encuentran usuarios
            $usuarioConMenosTickets = $usuarios->random();
        }

        $ticket = Ticket::create([
            'name' => $this->name,
            'data' => $this->data,
            'category_id' => (int) $this->categoria,
            'user_id' => auth()->user()->id,
            'status_id' => 1,
            'support_id' => $usuarioConMenosTickets->id,
            'priority_id' => 1
        ]);

        $Notificacion = [
            'name' => auth()->user()->name,
            'lastname'   => auth()->user()->lastname,
            'email' => auth()->user()->email,
            'name_ticket' => $ticket->name,
            'department' => auth()->user()->employee->position->department->name,
            'data' => $ticket->data,
            'tiempo' => $ticket->created_at,
            'categoria' => $category->name,
            'username' => $usuarioConMenosTickets->name
        ];

        $usuarioConMenosTickets->notify(new SoporteNotification($Notificacion));
        //  $support_notification= new FirebaseNotificationController();
        //  $support_notification->supportNotification(auth()->user()->name,$this->name,$usuarioConMenosTickets->id);


        Historial::create(
            [
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id,
                'type' => 'creado',
                'data' => $ticket->data

            ]
        );
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
        // dd($ticketEditar->support);
        $category = Categoria::find($ticketEditar->category_id);
        if ($this->data == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('data', 'La descripcion es obligatoria');
            return;
        }

        $this->validate(
            [
                'name' => 'required',
                'data' => 'required|max:10000000',
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

        $notificacionEditar = [
            'name' => auth()->user()->name,
            'last_name' => auth()->user()->lastname,
            'name_ticket' => $ticketEditar->name,
        ];

        $ticketEditar->support->notify(new EditarTicketNotification($notificacionEditar));
        // $support_notification_edit= new FirebaseNotificationController();
        // $support_notification_edit->supportEditNotification(auth()->user()->name,$ticketEditar->name,$ticketEditar->support->id);
        $this->name = '';
        $this->categoria = '';
        $this->dispatchBrowserEvent('editar');
    }

    public function finalizarTicket($id)
    {
        $actualizar_status = Ticket::find($id);
        $category = Categoria::find($actualizar_status->category_id);
        $usuarios = $category->usuarios;
        $actualizar_status->update(
            [

                'status_id' => 4
            ]
        );
        Historial::create(

            [
                'ticket_id' => $actualizar_status->id,
                'user_id' => auth()->user()->id,
                'type' => 'status_finished',
                'data' => $actualizar_status->status->name
            ]
        );
        $NotificacionStatus =
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'name_ticket' => $actualizar_status->name,
                'status' => $actualizar_status->status->name,
                'username' => $usuarios['0']->name
            ];

        $actualizar_status->support->notify(new StatuSoporteFinalizadoNotification($NotificacionStatus));
        // $support_finished= new FirebaseNotificationController();
        // $support_finished->supportFinishedTicket(auth()->user()->name, $actualizar_status->name,$actualizar_status->support->id);
    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->especial=$ticket->special;
        $this->estrellas = $ticket->score;
        $this->prioridad = $ticket->priority->time;
        $this->mensajes = $ticket;
        $this->ticket_solucion = $ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
        $this->dispatchBrowserEvent('cargar');
    }

    public function enviarMensaje()
    {

        $ticket = Ticket::find($this->ticket_id);
        $category = Categoria::find($ticket->category_id);

        $this->validate([
            'mensaje' => 'required|max:10000000'
        ]);


        Historial::create(

            [
                'ticket_id' => $this->ticket_id,
                'user_id' => auth()->user()->id,
                'type' => 'Mensaje',
                'data' => $this->mensaje
            ]
        );

        if ($ticket->status_id == 1) {
            Mensaje::create([
                'ticket_id' => $this->ticket_id,
                'mensaje' => $this->mensaje,
                'user_id' => auth()->user()->id
            ]);
        } else {
            Mensaje::create([
                'ticket_id' => $this->ticket_id,
                'mensaje' => $this->mensaje,
                'user_id' => auth()->user()->id
            ]);
            $ticket->update([
                'status_id' => 2
            ]);
        }
        $notificationMessage = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticket->name
        ];
        $ticket->support->notify(new MessageSoporteNotification($notificationMessage));
        $this->dispatchBrowserEvent('Mensaje');
        // $support_message=new FirebaseNotificationController();
        // $support_message->supportMessage(auth()->user()->name, $ticket->name,$ticket->support->id);
    }

    function encuesta()
    {
        $ticket = Ticket::find($this->ticket_id);
        $category = Categoria::find($ticket->category_id);
        $usuarios = $category->usuarios;
        $this->validate([
            'score' => 'required',
            'comments' => 'required|max:255',
        ]);

        encuesta::create([
            'ticket_id' => $this->ticket_id,
            'support_id' => $ticket->support_id,
            'score' => $this->score,
            'comments' => $this->comments
        ]);

        Historial::create(
            [
                'ticket_id' => $this->ticket_id,
                'user_id' => auth()->user()->id,
                'type' => 'Encuesta',
                'data' => $this->score
            ]
        );

        $notificationEncuesta = [
            'score' => $ticket->score->score,
            'name_ticket' => $ticket->name
        ];

        $category = Categoria::find($ticket->category_id);
        $this->dispatchBrowserEvent('Encuesta');
        $this->score='';
        $this->comments='';
    }
}
