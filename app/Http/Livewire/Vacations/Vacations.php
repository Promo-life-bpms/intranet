<?php

namespace App\Http\Livewire\Vacations;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Vacations extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('admin.vacations.table-vacations', [
            'users' => User::paginate(10),
        ]);
    }

    public function updateDays(Vacations $data)
    {
        if ((int) $this->daysEnjoyed < 0) {
            session()->flash('error', 'Ingrese un dato valido.');
            return;
        }
        $daysCalculed = (int) $data->days_availables;
        $daysAvailables =  $daysCalculed - (int) $this->daysEnjoyed;
        $data->update([
            "dv" => $daysAvailables,
            "days_enjoyed" => (int) $this->daysEnjoyed,
        ]);
        $this->data = $data;
        session()->flash('message', 'Actualizacion Correcta.');
    }
}
