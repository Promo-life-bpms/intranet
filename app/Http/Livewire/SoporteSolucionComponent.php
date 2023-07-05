<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Soporte\Ticket;
use App\Models\Soporte\Mensaje;
use App\Models\Soporte\Solucion;
use App\Models\Soporte\Historial;
use App\Models\SoporteTiempo;
use App\Models\User;
use App\Notifications\MessageSoporteSolutionNotification;
use App\Notifications\SolucionSoporteNotification;
use App\Notifications\StatusEnProcesoSoporteNotification;
use App\Notifications\ReasignacionTicketSoporte;
use Hamcrest\Core\HasToString;

class SoporteSolucionComponent extends Component
{
    use WithPagination;
    public $ticket_id, $name, $categoria, $data, $categorias, $description, $mensaje, $status, $historial, $usuario, $mensajes, $usuario_reasignacion, $tiempo, $estrellas,
        $comments, $prioridad, $time,$especial;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['ocultarBoton'];

    public function render()
    {
        //traer los tipos de prioridad
        $priority = SoporteTiempo::where('id', '>', 1)->get();
        $categories =  auth()->user()->asignacionCategoria->pluck(["id"]);
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        $ticketReasignado = Ticket::where('support_id', auth()->user()->id)->get();
        return view('livewire.soporte-solucion-component', [

            'solucion' => Ticket::where('support_id', auth()->user()->id)->simplePaginate(15)
        ], compact('users', 'ticketReasignado', 'priority'));
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
        // dd($ticket);
        $this->especial=$ticket->special;
        $this->estrellas = $ticket->score;
        $this->comments = $ticket->score;
        $this->prioridad = $ticket->priority->time;
        $this->usuario = $ticket->user;
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
                'description' => 'required|max:10000000'
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

        if ($this->mensajes == trim('<p><br data-cke-filler="true"></p>')) {
            $this->addError('mensaje', 'La descripcion es obligatoria');
            return;
        }

        $this->validate([
            'mensajes' => 'required|max:10000000'
        ]);


        Mensaje::create([
            'ticket_id' => $this->ticket_id,
            'mensaje' => $this->mensajes,
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

    public function reasignar()
    {
        $ticket = Ticket::find($this->ticket_id);


        $this->validate(
            [
                'usuario_reasignacion' => 'required'
            ]
        );

        $ticket->update([
            'support_id' => $this->usuario_reasignacion
        ]);

        //aqui busco al usuario que se guarda para reasignar
        $user = User::find($this->usuario_reasignacion);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => auth()->user()->id,
            'type' => 'Reasignacion',
            'data' => $user->name
        ]);


        $reasignacionTicket = [
            'name' => auth()->user()->name,
            'name_ticket' => $ticket->name,
        ];

        //aqui envio la notificacion al usuario
        $user->notify(new ReasignacionTicketSoporte($reasignacionTicket));
        $this->dispatchBrowserEvent('reasignacion');
    }
    public function time($id)
    {

        $ticket = Ticket::find($id);
        $usuario = $ticket->user;

        $this->validate([
            'tiempo' => 'required'
        ]);

        $ticket->update(
            [
                'priority_id' => $this->tiempo
            ]
        );

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => auth()->user()->id,
            'type' => 'Tiempo',
            'data' => $ticket->priority->time
        ]);


        //notificamos
        // $notificationPriority=[
        //     'name'=>auth()->user()->name,
        //     'name_ticket'=>$ticket->name,
        //     'time'=>$ticket->priority->time
        // ];

        // $usuario->notify(new SoportePrioridadNotification($notificationPriority));
        $this->dispatchBrowserEvent('Tiempo');
    }


    public function special()
    {
        $ticket=Ticket::find($this->ticket_id);

        $ticket->update([
            'special' => $this->time,
        ]);



        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => auth()->user()->id,
            'type' => 'Tiempo',
            'data' => $ticket->special
        ]);


        $this->dispatchBrowserEvent('special');
    }
}

