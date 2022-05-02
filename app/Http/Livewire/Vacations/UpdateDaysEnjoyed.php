<?php

namespace App\Http\Livewire\Vacations;

use App\Models\Vacations;
use Livewire\Component;

class UpdateDaysEnjoyed extends Component
{
    public $data;
    public $daysEnjoyed;

    public function render()
    {
        return view('admin.vacations.update-days-enjoyed');
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
