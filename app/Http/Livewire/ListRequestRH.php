<?php

namespace App\Http\Livewire;

use App\Events\RHResponseRequestEvent;
use App\Models\Employee;
use App\Models\Request;
use App\Models\RequestRejected;
use App\Notifications\RHResponseRequestNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ListRequestRH extends Component
{
    use WithPagination;
    public $searchName, $searchStatus;
    public function render()
    {
        $user = '%' . $this->searchName . '%';
        $status = $this->searchStatus;
        $operadorStatus = '=';
        if ($this->searchStatus == "") {
            $status = null;
            $operadorStatus = '!=';
        }
        $requests = Request::join('employees', 'requests.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('requests.direct_manager_status', 'Aprobada')
            ->where('requests.human_resources_status', $operadorStatus, $status)
            ->where('users.name', 'LIKE', $user)
            ->orderBy('requests.created_at', 'DESC')
            ->select('requests.*')
            ->simplePaginate(10);
        return view('livewire.list-request-r-h', ['requests' => $requests]);
    }

    public function autorizar(Request $request)
    {
        $request->human_resources_status = "Aprobada";
        if ($request->type_request == "Solicitar vacaciones") {
            $user = Employee::find($request->employee_id)->user;
            $totalDiasSolicitados = count($request->requestdays);
            $totalDiasDisponibles = $user->vacationsAvailables()->orderBy('period', 'ASC')->sum('dv');
            if ((int) $totalDiasDisponibles >= $totalDiasSolicitados) {
                foreach ($user->vacationsAvailables()->orderBy('period', 'ASC')->get() as $dataVacation) {
                    if ($dataVacation->dv <= $totalDiasSolicitados && $totalDiasSolicitados == count($request->requestdays)) {
                        if ($dataVacation->dv > 0) {
                            $dataVacation->days_enjoyed = (int) $dataVacation->days_availables;
                            $totalDiasSolicitados = (int)$totalDiasSolicitados - (int) $dataVacation->dv;
                            $dataVacation->dv = 0;
                            $dataVacation->vacationsAvailables()->save();
                        }
                    } else {
                        $dataVacation->days_enjoyed = $dataVacation->days_enjoyed + $totalDiasSolicitados;
                        $dataVacation->dv = $dataVacation->dv - $totalDiasSolicitados;
                        $dataVacation->save();
                        break;
                    }
                }
            } else {
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
        $requestCalendar = $request->requestdays;
        foreach ($requestCalendar as $calendar) {
            $rejectedCalendar = new RequestRejected();
            $rejectedCalendar->title = $calendar->title;
            $rejectedCalendar->start = $calendar->start;
            $rejectedCalendar->end = $calendar->end;
            $rejectedCalendar->users_id = $calendar->users_id;
            $rejectedCalendar->requests_id = $calendar->requests_id;
            $rejectedCalendar->save();
        }
        //prueba
        $request->human_resources_status = "Aprobada";
        if ($request->type_request == "Solicitar vacaciones") {
             $request->human_resources_status = "Rechazada";
            $user = Employee::find($request->employee_id)->user;
            $totalDiasSolicitados = count($request->requestdays);
            $totalDiasDisponibles = $user->vacationsAvailables()->orderBy('period', 'ASC')->sum('dv');
            if ((int) $totalDiasSolicitados >= $totalDiasDisponibles) {
                foreach ($user->vacationsAvailables()->orderBy('period', 'ASC')->get() as $dataVacation) {
                    if ($dataVacation->dv >= $totalDiasSolicitados && $totalDiasSolicitados == count($request->requestdays)) {
                  
                        if ($dataVacation->dv < 0) {
                            $dataVacation->days_enjoyed = (int) $dataVacation->days_availables;
                            $totalDiasSolicitados = (int)$totalDiasSolicitados + (int) $dataVacation->dv;
                            $dataVacation->dv = 0;
                            $dataVacation->save();
                        }
                    } else {
                       
                        $dataVacation->days_enjoyed = $dataVacation->days_enjoyed - $totalDiasSolicitados;
                        $dataVacation->dv = $dataVacation->dv + $totalDiasSolicitados;
                        $dataVacation->save();
                        
                        break;
                    }
                }
            } else {
                return 2;
            }
        }
                
        
                //
        $request->requestdays()->delete();
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
