<?php

namespace App\Http\Livewire\Vacations;

use App\Models\User;
use App\Models\Vacations as ModelsVacations;
use Livewire\Component;
use Livewire\WithPagination;

class Vacations extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public  $keyWord, $daysEnjoyed;


    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $users = User::orWhere('name', 'LIKE', $keyWord)
            ->orWhere('lastname', 'LIKE', $keyWord)
            ->paginate(10);
        return view('admin.vacations.table-vacations', [
            'users' => $users,
        ]);
    }

    public function updateDays($vacation_id, $user_id, $period_id)
    {

        $daysEnjoyed = $this->daysEnjoyed[$user_id][$period_id];
        $data = ModelsVacations::find($vacation_id);
        if ((int) $daysEnjoyed < 0) {
            session()->flash('error', 'Ingrese un dato valido.');
            return;
        }
        $daysCalculed = (int) $data->days_availables;
        $daysAvailables =  $daysCalculed - $daysEnjoyed;
        $data->update([
            "dv" => $daysAvailables,
            "days_enjoyed" => (int) $daysEnjoyed,
        ]);
        $this->daysEnjoyed = null;
        session()->flash('message', 'Actualizacion Correcta.');
    }
}
