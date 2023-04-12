<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Soporte\UsuariosSoporte;
use Livewire\Component;
use App\Models\Role;

class SoporteAdminComponent extends Component
{
    public function render()
    {
        return view('livewire.soporte-admin-component');

    }
}
