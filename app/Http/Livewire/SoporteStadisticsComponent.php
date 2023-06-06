<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\Soporte\Ticket;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\User;

class SoporteStadisticsComponent extends Component
{


    public $labels = [], $meses = [], $usuario = [], $name = [];
    public $values = [], $ticketsPorMes = [], $ticketCounts = [], $totalTicket = [];


    public function mount()
    {

        //traer la cantidad de tickets que ha echo un usuario
        $usuarios = User::has('tickets')->get();
        $this->name = $usuarios->pluck('name')->toArray();
        $this->totalTicket = [];

        foreach ($usuarios as $usuario) {
            $ticket = Ticket::where('user_id', $usuario->id)->count();
            $this->totalTicket[] = $ticket;
        }




        //aqui me trae a todos los usuarios con el rol de sistemas
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        //me trae el nombre de los usuarios que dan soporte
        $this->usuario = $users->pluck('name')->toArray();

        //para traer la cantidad de tickets por usuario de soporte
        // $tickets=Ticket::whereIn('category_id',$categories)->count();
        // dd($tickets);

        $category = Categoria::all();
        $this->labels = $category->pluck('name')->toArray();

        foreach ($category as $categoria) {
            $ticketsCount = Ticket::where('status_id', 4)->where('category_id', $categoria->id)->count();
            $this->values[] = $ticketsCount;
        }
        $this->values = collect($this->values)->toArray();

        $tickets = Ticket::where('status_id', 4)->get();
        //   // Agrupar los tickets por mes
        $ticketsByMonth = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('F Y');
        });

        //   // Obtener los meses y contar los tickets por mes
        foreach ($ticketsByMonth as $month => $monthTickets) {
            $this->meses[] = $month;
            $this->ticketsPorMes[] = $monthTickets->count();
        }
    }


    public function render()
    {
        $ticketsResueltos = Ticket::where('status_id', 4)->count();
        $ticketsEnProceso = Ticket::where('status_id', 2)->count();
        $ticketsCreados = Ticket::all()->count();

        return view('livewire.soporte-stadistics-component', compact('ticketsResueltos', 'ticketsEnProceso', 'ticketsCreados'));
    }
}
