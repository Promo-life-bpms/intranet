<?php

namespace App\Http\Livewire;

use App\Models\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ListAbsenseReport extends Component
{
    use WithPagination;
    public $start, $end;

    public function render()
    {
        $requests = Request::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->orderBy('updated_at', 'DESC')->simplePaginate(3);
        return view('livewire.list-absense-report', ['requests' => $requests]);
    }

    public function buscar()
    {

    }
}
