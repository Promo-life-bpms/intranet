<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\User;
use App\Models\Soporte\Ticket;
use Livewire\Component;
use Carbon\Carbon;


class SoporteAdminComponent extends Component
{
    public $categorias, $name, $usuario_id,$user;

    public $labels = [];
    public $values = [];
    public $ticketsInProcess = [];


    public function mount()
    {
       //Lógica para obtener los datos del gráfico desde la base de datos
        $category=Categoria::all();
        $this->labels=$category->pluck('name')->toArray();

        foreach($category as $categoria){
            $ticketsCount=Ticket::where('status_id',4)->where('category_id',$categoria->id)->count();
            $ticketsCountinProcess=Ticket::where('status_id',2)->where('category_id',$categoria->id)->count();
            $this->values[]=$ticketsCount;
            $this->ticketsInProcess[]=$ticketsCountinProcess;
        }
        $this->values = collect($this->values)->toArray();
        $this->ticketsInProcess=collect($this->ticketsInProcess)->toArray();

      // Obtener los datos del gráfico desde la base de datos
    //   $tickets = Ticket::where('status_id', 4)->get();

    //   // Agrupar los tickets por mes
    //   $ticketsByMonth = $tickets->groupBy(function ($ticket) {
    //       return Carbon::parse($ticket->created_at)->format('F Y');
    //   });

    //   // Obtener los meses y contar los tickets por mes
    //   foreach ($ticketsByMonth as $month => $monthTickets) {
    //       $this->labels[] = $month;
    //       $this->values[] = $monthTickets->count();
    //   }

    }


    public function render()
    {

        $this->categorias = Categoria::orderBy('id')->get();
        //aqui me trae a todos los usuarios con el rol de sistemas
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

            return   view('livewire.soporte-admin-component',compact('users'));
            
    }


    public function verAsignacion($id)
    {
        $usuario = User::find($id);
        $this->usuario_id = $usuario->id;
        $this->name = $usuario->name;
        $this->user=$usuario;

    }


    public function asignacion($categorias)
    {
        $usuario = User::find($this->usuario_id);
        $usuario->asignacionCategoria()->toggle([$categorias]);

    }


}
