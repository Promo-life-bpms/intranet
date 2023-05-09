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
use App\Models\Soporte\Historial;
use App\Notifications\SolucionSoporteNotification;
use App\Notifications\StatusEnProcesoSoporteNotification;

class SoporteSolucionComponent extends Component
{
    use WithPagination;
    public $ticket_id, $name, $categoria, $data, $categorias, $description,$mensaje,$status,$historial;
    protected $paginationTheme = 'bootstrap';
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
        $usuarios=$actualizar_status->user;
        // dd($actualizar_status->user);

        $actualizar_status->update([
            'status_id' => 2
        ]);

        Historial::create([
            'ticket_id' =>$actualizar_status->id,
            'user_id'=>auth()->user()->id,
            'type'=>'status',
            'data'=>$actualizar_status->status->name
        ]);
        //CHECAR NOTIFICACION EN PROCESO
        $notificacionEnProceso=[
            'name'=>auth()->user()->name,
            'name_ticket'=>$actualizar_status->name,
            'status'=>$actualizar_status->status
        ];

        $usuarios->notify(new StatusEnProcesoSoporteNotification($notificacionEnProceso));

    }

    public function verTicket($id)
    {
        $ticket = Ticket::find($id);
        $this->historial=$ticket;
        $this->mensaje=$ticket;
        $this->ticket_id = $ticket->id;
        $this->name = $ticket->name;
        $this->data = $ticket->data;
        $this->categoria = $ticket->category->name;
    }

    public function guardarSolucion()
    {

        $ticket=Ticket::find($this->ticket_id);
        $usuario=$ticket->user;
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

        Historial::create([
            'ticket_id' =>$this->ticket_id,
            'user_id'=>auth()->user()->id,
            'type'=>'solucion',
            'data'=>$this->description
        ]);


        $solucionNotification=[
            'name'=>auth()->user()->name,
            'name_ticket'=>$ticket->name,
        ];

        $usuario->notify(new SolucionSoporteNotification($solucionNotification));

        $this->dispatchBrowserEvent('ticket_solucion');
    }
}
