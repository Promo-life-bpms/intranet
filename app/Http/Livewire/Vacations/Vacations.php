<?php

namespace App\Http\Livewire\Vacations;

use App\Models\User;
use App\Models\Vacations as ModelsVacations;
use Illuminate\Support\Facades\DB;
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
        DB::statement("SET SQL_MODE=''");
        $users = User::join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'roles.id', 'role_user.role_id')
            ->where('users.status', '=', 1)
            ->whereIn('roles.id', [3, 7])
            ->groupBy('users.id')
            ->select('users.id')
            ->get();
        $users = User::where('name', 'LIKE', $keyWord)
            ->where('status', '=', 1)
            ->whereNotIn('id', $users->pluck('id')->toArray())
            ->paginate(10);

        return view('admin.vacations.table-vacations', [
            'users' => $users,
        ]);
    }

    public function updateDays($vacation_id, $user_id, $id)
    {
        if ($this->daysEnjoyed) {
            $daysEnjoyed = $this->daysEnjoyed[$user_id][$id];
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
}
