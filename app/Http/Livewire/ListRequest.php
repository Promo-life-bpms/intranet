<?php

namespace App\Http\Livewire;

use App\Events\ManagerResponseRequestEvent;
use App\Models\Employee;
use App\Models\Request;
use App\Models\Role;
use App\Notifications\ManagerResponseRequestNotification;
use App\Notifications\ManagerResponseRequestToRHNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ListRequest extends Component
{
    use WithPagination;

    public function render()
    {
        $requests = auth()->user()->employee->requestToAuth()->orderBy('created_at', 'DESC')->simplePaginate(10);
        return view('livewire.list-request', ['requests' => $requests]);
    }

    public function autorizar(Request $request)
    {
        $request->direct_manager_status = "Aprobada";
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        event(new ManagerResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->direct_manager_status));
        $userReceiver->notify(new ManagerResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->direct_manager_status));
        $usersRH = Role::where('name', 'rh')->first()->users;
        if (!auth()->user()->hasRole('rh')) {
            foreach ($usersRH as $userRH) {
                $userRH->notify(new ManagerResponseRequestToRHNotification($request->type_request, $userReceiver->name . ' ' . $userReceiver->lastname,  $user->name . ' ' . $user->lastname));
            }
        }
        return 1;
    }
    public function rechazar(Request $request)
    {
        $request->direct_manager_status = "Rechazada";
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        event(new ManagerResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->direct_manager_status));
        $userReceiver->notify(new ManagerResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->direct_manager_status));
        return 1;
    }

    public function auth($id)
    {
        $this->dispatchBrowserEvent('swal', ['id' => $id]);
    }
}
