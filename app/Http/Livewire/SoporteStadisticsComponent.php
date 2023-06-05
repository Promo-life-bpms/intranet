<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\Soporte\Ticket;
use Carbon\Carbon;
use Livewire\Component;

class SoporteStadisticsComponent extends Component
{

    public $totalticket;
    public $labels = [],$meses=[];
    public $values = [],$ticketsPorMes=[];


    //Tickets resueltos por categorias
    public function mount()
    {
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
        $ticketsResueltos=Ticket::where('status_id',4)->count();
        $ticketsEnProceso=Ticket::where('status_id',2)->count();
        return view('livewire.soporte-stadistics-component',compact('ticketsResueltos','ticketsEnProceso'));
        
    }
}
