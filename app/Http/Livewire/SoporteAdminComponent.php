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

      

    }


    public function render()
    {

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

        $this->categorias = Categoria::orderBy('id')->get();
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
