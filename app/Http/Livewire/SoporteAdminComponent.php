<?php

namespace App\Http\Livewire;

use App\Models\Soporte\Categoria;
use App\Models\User;
use Livewire\Component;



class SoporteAdminComponent extends Component
{
    public $categoria;


    public function render()
    {


        $this->categoria = Categoria::orderBy('id')->get();

        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        return view('livewire.soporte-admin-component');
    }
}
