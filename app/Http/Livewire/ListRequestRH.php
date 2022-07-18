<?php

namespace App\Http\Livewire;

use App\Events\RHResponseRequestEvent;
use App\Models\Employee;
use App\Models\Request;
use App\Notifications\RHResponseRequestNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ListRequestRH extends Component
{
    use WithPagination;

    public function render()
    {
        $requests = Request::where('direct_manager_status', 'Aprobada')->orderBy('created_at', 'DESC')->simplePaginate(10);
        return view('livewire.list-request-r-h', ['requests' => $requests]);
    }

    public function autorizar(Request $request)
    {
        $request->human_resources_status = "Aprobada";
        if ($request->type_request == "Solicitar vacaciones") {
            $user = Employee::find($request->employee_id)->user;
            $totalDiasSolicitados = count($request->requestdays);
            $totalDiasDisponibles = $user->vacationsAvailables()->orderBy('period', 'DESC')->sum('dv');
            if ((int) $totalDiasDisponibles >= $totalDiasSolicitados) {
                foreach ($user->vacationsAvailables()->orderBy('period', 'DESC')->get() as $dataVacation) {
                    if ($dataVacation->dv < $totalDiasSolicitados) {
                        $dataVacation->days_enjoyed = $dataVacation->dv;
                        $totalDiasSolicitados = $totalDiasSolicitados - $dataVacation->dv;
                        $dataVacation->dv = 0;
                        $dataVacation->save();
                    } else {
                        $dataVacation->days_enjoyed = $dataVacation->days_enjoyed + $totalDiasSolicitados;
                        $dataVacation->dv = $dataVacation->dv - $totalDiasSolicitados;
                        $dataVacation->save();
                        break;
                    }
                }
            }else{
                return 2;
            }
        }
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        event(new RHResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->human_resources_status));
        $userReceiver->notify(new RHResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->human_resources_status));
        return 1;
    }

    public function rechazar(Request $request)
    {
        $request->human_resources_status = "Rechazada";
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        event(new RHResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->human_resources_status));
        $userReceiver->notify(new RHResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->human_resources_status));
        return 1;
    }

    public function auth($id)
    {
        $this->dispatchBrowserEvent('swal', ['id' => $id]);
    }
}
