<?php

namespace App\Http\Livewire;

use App\Events\ManagerResponseRequestEvent;
use App\Http\Controllers\FirebaseNotificationController;
use App\Models\Employee;
use App\Models\Request;
use App\Models\RequestRejected;
use App\Models\Role;
use App\Notifications\ManagerResponseRequestNotification;
use App\Notifications\ManagerResponseRequestToRHNotification;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Clase ListRequest
 *
 * Esta clase es responsable de manejar la lista de solicitudes y las acciones de autorizar y rechazar una solicitud.
 * Utiliza la paginación para mostrar las solicitudes en grupos de 10.
 */
class ListRequest extends Component
{
    use WithPagination;

    /**
     * Método render
     *
     * Este método renderiza la vista de la lista de solicitudes.
     * Obtiene las solicitudes del usuario autenticado y las ordena por fecha de creación descendente.
     * Utiliza la paginación simple para mostrar 10 solicitudes por página.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $requests = auth()->user()->employee->requestToAuth()->orderBy('created_at', 'DESC')->simplePaginate(10);
        return view('livewire.list-request', ['requests' => $requests]);
    }

    /**
     * Método autorizar
     *
     * Este método se utiliza para autorizar una solicitud.
     * Actualiza el estado de la solicitud a "Aprobada".
     * Envía notificaciones al usuario solicitante y al departamento de recursos humanos.
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function autorizar(Request $request)
    {
        $request->direct_manager_status = "Aprobada";
        $request->save();
        $user = auth()->user();
        $userReceiver = Employee::find($request->employee_id)->user;
        //Notificaciones
        $communique_notification = new FirebaseNotificationController();
        $communique_notification->sendToRh();
        try {
            event(new ManagerResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->direct_manager_status));
            $userReceiver->notify(new ManagerResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->direct_manager_status));
        } catch (Exception $e) {
            Storage::put('/public/dataErrorVacation' .  $userReceiver->id . '.txt', $e->getMessage());
        }
        $usersRH = Role::where('name', 'rh')->first()->users()->where('status', 1)->get();
        if (!auth()->user()->hasRole('rh')) {
            foreach ($usersRH as $userRH) {
                try {
                    $userRH->notify(new ManagerResponseRequestToRHNotification($request->type_request, $userReceiver->name . ' ' . $userReceiver->lastname,  $user->name . ' ' . $user->lastname));
                } catch (Exception $e) {
                    Storage::put('/public/dataErrorVacation' .  $userRH->id . '.txt', $e->getMessage());
                }
            }
        }
        return 1;
    }

    /**
     * Método rechazar
     *
     * Este método se utiliza para rechazar una solicitud.
     * Actualiza el estado de la solicitud a "Rechazada".
     * Guarda los días de la solicitud rechazada en una tabla separada.
     * Envía notificaciones al usuario solicitante.
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function rechazar(Request $request)
    {
        $request->direct_manager_status = "Rechazada";
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

        $communique_notification = new FirebaseNotificationController();
        $communique_notification->sendRejectedRequest($userReceiver->id);

        event(new ManagerResponseRequestEvent($request->type_request, $request->direct_manager_id,  $user->id,  $user->name . ' ' . $user->lastname, $request->direct_manager_status));
        $userReceiver->notify(new ManagerResponseRequestNotification($request->type_request, $user->name . ' ' . $user->lastname, $userReceiver->name . ' ' . $userReceiver->lastname, $request->direct_manager_status));
        return 1;
    }

    /**
     * Método auth
     *
     * Este método se utiliza para autenticar al usuario.
     * Despacha un evento de navegador para mostrar una alerta.
     *
     * @param int $id
     * @return void
     */
    public function auth($id)
    {
        $this->dispatchBrowserEvent('swal', ['id' => $id]);
    }
}
