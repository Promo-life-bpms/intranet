<?php

namespace App\Http\Livewire;

use App\Events\RHResponseRequestEvent;
use App\Http\Controllers\FirebaseNotificationController;
use App\Models\Employee;
use App\Models\Request;
use App\Models\RequestRejected;
use App\Notifications\RHResponseRequestNotification;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Clase ListRequestRH
 *
 * Esta clase es responsable de manejar la lista de solicitudes de recursos humanos.
 * Contiene métodos para renderizar la vista, autorizar y rechazar solicitudes.
 */
class ListRequestRH extends Component
{
    use WithPagination;
    public $searchName, $searchStatus;

    /**
     * Renderiza la vista de la lista de solicitudes de recursos humanos.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // Código para obtener las solicitudes de recursos humanos y las solicitudes sin autorización
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
        $requestsWithoutAuth = Request::join('employees', 'requests.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('requests.direct_manager_status', 'Pendiente')
            ->where('users.status', 1)
            ->where('requests.human_resources_status', 'Pendiente')
            ->orderBy('requests.created_at', 'DESC')
            ->select('requests.*')
            ->get();
        return view('livewire.list-request-r-h', [
            'requests' => $requests,
            'requestsWithoutAuth' => $requestsWithoutAuth
        ]);
    }

    /**
     * Autoriza una solicitud de recursos humanos.
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function autorizar(Request $request)
    {
        if ($request->human_resources_status !== "Pendiente") {
            return 3;
        }
        $request->human_resources_status = "Aprobada";

        if ($request->type_request == "Solicitar vacaciones") {
            $user = Employee::find($request->employee_id)->user;
            $totalDiasSolicitados = count($request->requestdays);
            $totalDiasDisponibles = $user->employee->take_expired_vacation
                ? $user->vacationsComplete()->orderBy('period', 'DESC')->sum('dv')
                : $user->vacationsAvailables()->orderBy('period', 'DESC')->sum('dv');
            if ((int) $totalDiasDisponibles >= $totalDiasSolicitados) {
                $dataToUpdate = $user->employee->take_expired_vacation
                    ? $user->vacationsComplete()->orderBy('period', 'DESC')->get()
                    : $user->vacationsAvailables()->orderBy('period', 'DESC')->get();
                foreach ($dataToUpdate as $dataVacation) {
                    if ($dataVacation->dv >= $totalDiasSolicitados) {
                        $dataVacation->dv -= $totalDiasSolicitados;
                        $dataVacation->days_enjoyed = floor($dataVacation->days_availables) - $dataVacation->dv;
                        $dataVacation->save();
                        break;
                    } else {
                        $totalDiasSolicitados -= $dataVacation->dv;
                        $dataVacation->dv = 0;
                        $dataVacation->days_enjoyed = floor($dataVacation->days_availables) - $dataVacation->dv;
                        $dataVacation->save();
                    }
                }
            } else {
                return 2;
            }
        }
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        //Notificaciones
        $communique_notification = new FirebaseNotificationController();
        $communique_notification->sendApprovedRequest($userReceiver->id);
        event(new RHResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->human_resources_status));
        $userReceiver->notify(new RHResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->human_resources_status));
        return 1;
    }

    /**
     * Rechaza una solicitud de recursos humanos.
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function rechazar(Request $request)
    {
        if ($request->human_resources_status !== "Pendiente") {
            return 3;
        }
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
        $request->requestdays()->delete();
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        //Notificaciones
        $communique_notification = new FirebaseNotificationController();
        $communique_notification->sendRejectedRequest($userReceiver->id);
        event(new RHResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->human_resources_status));
        $userReceiver->notify(new RHResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->human_resources_status));
        return 1;
    }
}
